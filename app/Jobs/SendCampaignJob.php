<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Services\UserSegmentResolver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCampaignJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 600;

    public function __construct(public int $campaignId) {}

    public function handle(UserSegmentResolver $segments): void
    {
        $campaign = EmailCampaign::find($this->campaignId);

        if (! $campaign || ! in_array($campaign->status, ['draft', 'scheduled', 'failed'])) {
            return;
        }

        $campaign->update(['status' => 'sending']);

        try {
            $sent = 0;

            $segments->query($campaign->segment)
                ->whereNotNull('email')
                ->chunkById(50, function ($users) use ($campaign, &$sent) {
                    foreach ($users as $user) {
                        $recipient = EmailCampaignRecipient::updateOrCreate(
                            [
                                'email_campaign_id' => $campaign->id,
                                'user_id' => $user->id,
                            ],
                            [
                                'email' => $user->email,
                                'sent_at' => null,
                                'error' => null,
                            ],
                        );

                        try {
                            Mail::to($user->email)->send(new CampaignMail($campaign, $user->id));
                            $recipient->update(['sent_at' => now()]);
                            $sent++;
                        } catch (\Throwable $e) {
                            Log::warning('Campaign send failed', [
                                'campaign_id' => $campaign->id,
                                'user_id' => $user->id,
                                'error' => $e->getMessage(),
                            ]);
                            $recipient->update(['error' => substr($e->getMessage(), 0, 1000)]);
                        }

                        // Throttle suave: 500ms entre envíos para no saturar SMTP cPanel.
                        usleep(500_000);
                    }
                });

            $campaign->update([
                'status' => 'sent',
                'sent_at' => now(),
                'recipients_count' => $sent,
            ]);
        } catch (\Throwable $e) {
            Log::error('Campaign job failed', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
            ]);

            $campaign->update(['status' => 'failed']);
        }
    }
}
