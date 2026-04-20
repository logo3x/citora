<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CronController extends Controller
{
    public function reminders(Request $request): JsonResponse
    {
        $this->guard($request);

        $exitCode = Artisan::call('appointments:send-reminders');
        $output = trim(Artisan::output());

        Log::info('Cron reminders ejecutado', [
            'exit_code' => $exitCode,
            'output' => $output,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => $exitCode === 0,
            'exit_code' => $exitCode,
            'output' => $output,
            'at' => now()->toIso8601String(),
        ]);
    }

    private function guard(Request $request): void
    {
        $expected = config('services.cron.secret');
        $provided = $request->query('key', $request->header('X-Cron-Secret'));

        if (! $expected) {
            throw new AccessDeniedHttpException('Cron secret not configured');
        }

        if (! hash_equals((string) $expected, (string) $provided)) {
            throw new AccessDeniedHttpException('Invalid cron key');
        }
    }
}
