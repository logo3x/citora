<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignJob;
use App\Models\EmailCampaign;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('campaigns:run-scheduled')]
#[Description('Dispatches email campaigns with status=scheduled whose scheduled_at has arrived.')]
class RunScheduledCampaigns extends Command
{
    public function handle(): int
    {
        $due = EmailCampaign::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at')
            ->limit(20)
            ->get();

        if ($due->isEmpty()) {
            $this->info('Campañas programadas listas para enviar: 0');

            return Command::SUCCESS;
        }

        foreach ($due as $campaign) {
            $this->line("→ Disparando #{$campaign->id} · {$campaign->subject}");
            SendCampaignJob::dispatchSync($campaign->id);
        }

        $this->info("Campañas programadas listas para enviar: {$due->count()}");

        return Command::SUCCESS;
    }
}
