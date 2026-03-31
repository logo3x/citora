<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class WhatsAppService
{
    private ?Client $client = null;

    private ?string $from = null;

    public function send(string $to, string $message): bool
    {
        return $this->sendMessage($to, ['body' => $message]);
    }

    /**
     * Send a template message with buttons.
     *
     * @param  array<string, string>  $variables
     */
    public function sendTemplate(string $to, string $contentSid, array $variables = []): bool
    {
        $params = ['contentSid' => $contentSid];

        if ($variables) {
            $params['contentVariables'] = json_encode($variables);
        }

        return $this->sendMessage($to, $params);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function sendMessage(string $to, array $params): bool
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $this->from = config('services.twilio.whatsapp_from');

        if (! $sid || ! $token || ! $this->from) {
            Log::warning('WhatsApp: Twilio no configurado', ['to' => $to]);

            return false;
        }

        $to = $this->formatNumber($to);

        try {
            if (! $this->client) {
                $this->client = new Client($sid, $token);
            }

            $params['from'] = $this->from;

            $this->client->messages->create($to, $params);

            Log::info('WhatsApp enviado', ['to' => $to]);

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp error: '.$e->getMessage(), ['to' => $to]);

            return false;
        }
    }

    private function formatNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (! str_starts_with($phone, '+')) {
            if (strlen($phone) === 10) {
                $phone = '57'.$phone;
            }

            $phone = '+'.$phone;
        }

        return 'whatsapp:'.$phone;
    }
}
