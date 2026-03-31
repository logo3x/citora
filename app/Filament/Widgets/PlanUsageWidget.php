<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class PlanUsageWidget extends Widget
{
    protected static ?int $sort = 2;

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

        // Count WhatsApp messages this month from logs
        $messagesThisMonth = DB::table('jobs')
            ->whereMonth('created_at', now()->month)
            ->count();

        return [
            'used' => $used,
            'limit' => $limit,
            'remaining' => $remaining,
            'percentage' => $percentage,
            'isBlocked' => $isBlocked,
            'isUnlocked' => $isUnlocked,
            'slug' => $business->slug,
            'period' => now()->translatedFormat('F Y'),
        ];
    }
}
