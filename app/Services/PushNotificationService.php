<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushNotificationService
{
    private ?WebPush $webPush = null;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function sendToUser(User $user, array $payload): int
    {
        $subscriptions = $user->pushSubscriptions()->get();

        if ($subscriptions->isEmpty()) {
            return 0;
        }

        return $this->sendToSubscriptions($subscriptions, $payload);
    }

    /**
     * @param  iterable<PushSubscription>  $subscriptions
     * @param  array<string, mixed>  $payload
     */
    public function sendToSubscriptions(iterable $subscriptions, array $payload): int
    {
        $webPush = $this->getWebPush();

        if (! $webPush) {
            return 0;
        }

        $body = json_encode($payload);
        $sent = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $sub = Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->p256dh_key,
                    'authToken' => $subscription->auth_token,
                    'contentEncoding' => $subscription->content_encoding ?: 'aesgcm',
                ]);

                $webPush->queueNotification($sub, $body);
                $sent++;
            } catch (\Throwable $e) {
                Log::warning('Push: error preparando notificación', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                PushSubscription::where('endpoint', $endpoint)->update(['last_used_at' => now()]);

                continue;
            }

            $statusCode = $report->getResponse()?->getStatusCode();

            if (in_array($statusCode, [404, 410], true)) {
                PushSubscription::where('endpoint', $endpoint)->delete();
                Log::info('Push: suscripción expirada eliminada', ['endpoint' => $endpoint]);

                continue;
            }

            Log::warning('Push: envío falló', [
                'endpoint' => $endpoint,
                'status' => $statusCode,
                'reason' => $report->getReason(),
            ]);
        }

        return $sent;
    }

    private function getWebPush(): ?WebPush
    {
        if ($this->webPush) {
            return $this->webPush;
        }

        $publicKey = config('services.webpush.public_key');
        $privateKey = config('services.webpush.private_key');
        $subject = config('services.webpush.subject');

        if (! $publicKey || ! $privateKey) {
            Log::warning('Push: VAPID keys no configuradas');

            return null;
        }

        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => $subject,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ]);

        $this->webPush->setReuseVAPIDHeaders(true);

        return $this->webPush;
    }
}
