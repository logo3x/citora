<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AppointmentsChart;
use App\Filament\Widgets\PlanUsageWidget;
use App\Filament\Widgets\ServicePopularityChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UpcomingAppointments;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getHeading(): string
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return 'Panel de Administración';
        }

        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Buenos días',
            $hour < 18 => 'Buenas tardes',
            default => 'Buenas noches',
        };

        return "{$greeting}, {$user->getGreetingName()}";
    }

    public function getSubheading(): ?string
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return now()->translatedFormat('l d \\d\\e F, Y');
        }

        if (! $user->business_id) {
            return null;
        }

        $business = $user->business;

        if (! $business) {
            return null;
        }

        if ($business->hasReachedMonthlyLimit() && ! $business->isUnlockedForPeriod()) {
            return '⚠️ Has alcanzado el límite de '.$business->monthly_appointment_limit.' citas/mes. Desbloquea para seguir recibiendo reservas.';
        }

        $remaining = $business->getRemainingAppointments();

        if ($remaining <= 20 && $remaining > 0) {
            return "📊 Te quedan {$remaining} citas disponibles este mes.";
        }

        return now()->translatedFormat('l d \\d\\e F, Y');
    }

    public function getWidgets(): array
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return [
                StatsOverview::class,
                AppointmentsChart::class,
                UpcomingAppointments::class,
            ];
        }

        return [
            PlanUsageWidget::class,
            StatsOverview::class,
            AppointmentsChart::class,
            ServicePopularityChart::class,
            UpcomingAppointments::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
