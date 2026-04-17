<?php

namespace App\Services;

use App\Contracts\MessagingChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HablameSmsService implements MessagingChannel
{
    /**
     * Hablame doesn't use WhatsApp templates — always fall back to plain text.
     *
     * @param  array<int|string, string>  $variables
     */
    public function sendTemplate(string $to, string $templateKey, array $variables, string $fallbackText): bool
    {
        return $this->send($to, $fallbackText);
    }

    public function send(string $to, string $message): bool
    {
        $account = config('services.hablame.account');
        $apiKey = config('services.hablame.api_key');
        $endpoint = config('services.hablame.endpoint', 'https://www.hablame.co/api/sms/v5/send/priority');

        if (! $account || ! $apiKey) {
            Log::warning('Hablame: no configurado', ['to' => $to]);

            return false;
        }

        $to = $this->formatNumber($to);

        try {
            $response = Http::withHeaders([
                'Account' => $account,
                'ApiKey' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($endpoint, [
                'toNumber' => $to,
                'sms' => $message,
                'flash' => 0,
                'request_dlvr_rcpt' => 1,
            ]);

            if ($response->successful()) {
                Log::info('Hablame SMS enviado', ['to' => $to, 'response' => $response->json()]);

                return true;
            }

            Log::error('Hablame error', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Hablame exception: '.$e->getMessage(), ['to' => $to]);

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
