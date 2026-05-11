<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OpenTrackController extends Controller
{
    public function track(int $campaignId, Request $request): Response
    {
        $userId = (int) $request->query('u');

        if ($campaignId > 0 && $userId > 0) {
            $recipient = EmailCampaignRecipient::where('email_campaign_id', $campaignId)
                ->where('user_id', $userId)
                ->first();

            if ($recipient && ! $recipient->opened_at) {
                $recipient->update(['opened_at' => now()]);

                EmailCampaign::where('id', $campaignId)->increment('opened_count');
            }
        }

        // 1x1 transparent GIF
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($gif, 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => (string) strlen($gif),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
