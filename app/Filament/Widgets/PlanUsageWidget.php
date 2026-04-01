<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PlanUsageWidget extends Widget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.plan-usage';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user->hasRole('business_owner') && $user->business_id !== null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $business = auth()->user()->business;
        $used = $business->getMonthlyAppointmentCount();
        $limit = $business->monthly_appointment_limit;
        $remaining = $business->getRemainingAppointments();
        $isBlocked = $business->hasReachedMonthlyLimit() && ! $business->isUnlockedForPeriod();
        $isUnlocked = $business->isUnlockedForPeriod();
        $percentage = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
        $expiresAt = $business->getUnlockExpiresAt();
        $daysLeft = $expiresAt ? max(0, (int) now()->diffInDays($expiresAt, false)) : null;

        return [
            'used' => $used,
            'limit' => $limit,
            'remaining' => $remaining,
            'percentage' => $percentage,
            'isBlocked' => $isBlocked,
            'isUnlocked' => $isUnlocked,
            'daysLeft' => $daysLeft,
            'expiresAt' => $expiresAt?->translatedFormat('d \\d\\e F, Y'),
            'slug' => $business->slug,
        ];
    }
}
