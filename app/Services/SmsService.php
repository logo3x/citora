<?php

namespace App\Services;

use App\Contracts\MessagingChannel;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsService implements MessagingChannel
{
    private ?Client $client = null;

    /**
     * SMS providers don't support templates — always fall back to plain text.
     *
     * @param  array<int|string, string>  $variables
     */
    public function sendTemplate(string $to, string $templateKey, array $variables, string $fallbackText): bool
    {
        return $this->send($to, $fallbackText);
    }

    public function send(string $to, string $message): bool
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.sms_from');

        if (! $sid || ! $token || ! $from) {
            Log::warning('SMS: Twilio no configurado', ['to' => $to]);

            return false;
        }

        $to = $this->formatNumber($to);

        $params = ['body' => $message];

        if (str_starts_with($from, 'MG')) {
            $params['messagingServiceSid'] = $from;
        } else {
            $params['from'] = $from;
        }

        try {
            if (! $this->client) {
                $this->client = new Client($sid, $token);
            }

            $this->client->messages->create($to, $params);

            Log::info('SMS enviado', ['to' => $to]);

            return true;
        } catch (\Exception $e) {
            Log::error('SMS error: '.$e->getMessage(), ['to' => $to]);

            return false;
        }
    }

    private function formatNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 10) {
            $phone = '57'.$phone;
        }

        return '+'.$phone;
    }
}
