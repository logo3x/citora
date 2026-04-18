<?php

namespace App\Services;

use App\Contracts\MessagingChannel;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class WhatsAppService implements MessagingChannel
{
    private ?Client $client = null;

    private ?string $from = null;

    public function send(string $to, string $message): bool
    {
        return $this->sendMessage($to, ['body' => $message]);
    }

    /**
     * Send a pre-approved WhatsApp template by its logical key.
     * Falls back to sending plain text if no Content SID is configured for the key.
     *
     * @param  array<int|string, string>  $variables
     */
    public function sendTemplate(string $to, string $templateKey, array $variables, string $fallbackText): bool
    {
        $contentSid = config("services.twilio.templates.{$templateKey}");

        if (! $contentSid) {
            Log::info('WhatsApp: sin template configurado, enviando texto libre', ['template' => $templateKey]);

            return $this->send($to, $fallbackText);
        }

        $params = ['contentSid' => $contentSid];

        if ($variables) {
            $params['contentVariables'] = json_encode($this->normalizeVariables($variables));
        }

        if ($this->sendMessage($to, $params)) {
            return true;
        }

        // Fallback: if sending with the template failed (pending approval, rejected,
        // invalid variables, etc.) try again as free-form text. This only succeeds
        // inside the 24h customer-initiated window.
        Log::warning('WhatsApp: envío con template falló, intentando texto libre', ['template' => $templateKey]);

        return $this->send($to, $fallbackText);
    }

    /**
     * Twilio expects contentVariables as a JSON object with string keys "1", "2", ...
     * Accept both associative and sequential arrays.
     *
     * @param  array<int|string, string>  $variables
     * @return array<string, string>
     */
    private function normalizeVariables(array $variables): array
    {
        $normalized = [];

        foreach ($variables as $key => $value) {
            $normalized[(string) $key] = (string) $value;
        }

        return $normalized;
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
