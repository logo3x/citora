<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function vapidKey(): JsonResponse
    {
        return response()->json([
            'key' => config('services.webpush.public_key'),
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
            'contentEncoding' => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        $subscription = PushSubscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'endpoint' => $validated['endpoint'],
            ],
            [
                'p256dh_key' => $validated['keys']['p256dh'],
                'auth_token' => $validated['keys']['auth'],
                'content_encoding' => $validated['contentEncoding'] ?? 'aesgcm',
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                'last_used_at' => now(),
            ],
        );

        return response()->json(['ok' => true, 'id' => $subscription->id]);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string'],
        ]);

        PushSubscription::where('user_id', auth()->id())
            ->where('endpoint', $validated['endpoint'])
            ->delete();

        return response()->json(['ok' => true]);
    }

    public function test(PushNotificationService $push): JsonResponse
    {
        $sent = $push->sendToUser(auth()->user(), [
            'title' => '🔔 Notificación de prueba',
            'body' => '¡Tus notificaciones de Citora funcionan!',
            'icon' => '/images/logo-light.png',
            'url' => '/admin',
        ]);

        return response()->json(['ok' => true, 'sent' => $sent]);
    }
}
