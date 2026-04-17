<?php

namespace App\Providers;

use App\Contracts\MessagingChannel;
use App\Policies\RolePolicy;
use App\Services\HablameSmsService;
use App\Services\SmsService;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessagingChannel::class, function ($app) {
            return match (config('services.twilio.channel', 'sms')) {
                'whatsapp' => $app->make(WhatsAppService::class),
                'hablame' => $app->make(HablameSmsService::class),
                default => $app->make(SmsService::class),
            };
        });
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        Gate::policy(Role::class, RolePolicy::class);
    }
}
