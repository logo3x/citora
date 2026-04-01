<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    public function handle(Request $request): string
    {
        $secret = config('services.deploy.secret');

        if (! $secret || $request->header('X-Deploy-Secret') !== $secret) {
            abort(403, 'Unauthorized');
        }

        Log::info('Deploy triggered');

        $output = [];

        // Run migrations
        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'Migrations: '.Artisan::output();

        // Clear and rebuild caches
        Artisan::call('config:cache');
        $output[] = 'Config cached';

        Artisan::call('route:cache');
        $output[] = 'Routes cached';

        Artisan::call('view:cache');
        $output[] = 'Views cached';

        // Storage link
        if (! file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $output[] = 'Storage linked';
        }

        Log::info('Deploy completed', ['output' => $output]);

        return implode("\n", $output);
    }
}
