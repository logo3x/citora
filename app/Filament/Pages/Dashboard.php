<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getHeading(): string
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return 'Panel de Administración';
        }

        return '¡Hola, '.$user->name.'!';
    }

    public function getSubheading(): ?string
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin') || ! $user->business_id) {
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

        return null;
    }
}
