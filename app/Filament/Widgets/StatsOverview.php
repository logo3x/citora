<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        if ($user->hasRole('super_admin')) {
            return $this->getSuperAdminStats();
        }

        $todayAppointments = Appointment::where('business_id', $businessId)
            ->whereDate('starts_at', today())
            ->where('status', '!=', AppointmentStatus::Cancelled)
            ->count();

        $monthAppointments = Appointment::where('business_id', $businessId)
            ->whereMonth('starts_at', now()->month)
            ->whereYear('starts_at', now()->year)
            ->count();

        $pendingAppointments = Appointment::where('business_id', $businessId)
            ->where('status', AppointmentStatus::Pending)
            ->where('starts_at', '>=', now())
            ->count();

        $activeServices = Service::where('business_id', $businessId)
            ->where('is_active', true)
            ->count();

        $activeEmployees = Employee::where('business_id', $businessId)
            ->where('is_active', true)
            ->count();

        $monthRevenue = Appointment::where('appointments.business_id', $businessId)
            ->where('appointments.status', AppointmentStatus::Completed)
            ->whereMonth('appointments.starts_at', now()->month)
            ->whereYear('appointments.starts_at', now()->year)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $business = Business::find($businessId);
        $remaining = $business->getRemainingAppointments();
        $limit = $business->monthly_appointment_limit;
        $isBlocked = $business->hasReachedMonthlyLimit() && ! $business->isUnlockedForPeriod();

        return [
            Stat::make('Citas hoy', $todayAppointments)
                ->description('Programadas para hoy')
                ->icon('heroicon-o-calendar-days')
                ->color('primary'),
            Stat::make('Plan del mes', $isBlocked ? 'BLOQUEADO' : "{$monthAppointments} / {$limit}")
                ->description($isBlocked ? 'Desbloquea para recibir más citas' : "{$remaining} citas restantes")
                ->icon($isBlocked ? 'heroicon-o-lock-closed' : 'heroicon-o-chart-bar')
                ->color($isBlocked ? 'danger' : ($remaining <= 20 ? 'warning' : 'info')),
            Stat::make('Ingresos del mes', '$ '.number_format($monthRevenue))
                ->description('Citas completadas')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Pendientes', $pendingAppointments)
                ->description('Por confirmar')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Servicios activos', $activeServices)
                ->icon('heroicon-o-clipboard-document-list')
                ->color('gray'),
            Stat::make('Empleados activos', $activeEmployees)
                ->icon('heroicon-o-user-group')
                ->color('gray'),
        ];
    }

    /**
     * @return array<Stat>
     */
    private function getSuperAdminStats(): array
    {
        $totalBusinesses = Business::count();
        $activeBusinesses = Business::where('is_active', true)->count();
        $totalUsers = User::count();
        $monthAppointments = Appointment::whereMonth('starts_at', now()->month)->whereYear('starts_at', now()->year)->count();
        $todayAppointments = Appointment::whereDate('starts_at', today())->count();

        $blockedBusinesses = Business::where('is_active', true)->get()->filter(
            fn ($b) => $b->hasReachedMonthlyLimit() && ! $b->isUnlockedForPeriod()
        )->count();

        $paidThisMonth = Payment::where('status', 'approved')
            ->where('period', now()->format('Y-m'))
            ->count();

        $revenueThisMonth = Payment::where('status', 'approved')
            ->where('period', now()->format('Y-m'))
            ->sum('amount');

        return [
            Stat::make('Negocios', "{$activeBusinesses} activos")
                ->description("{$totalBusinesses} registrados")
                ->icon('heroicon-o-building-storefront')
                ->color('primary'),
            Stat::make('Usuarios', $totalUsers)
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make('Citas hoy', $todayAppointments)
                ->description("{$monthAppointments} este mes")
                ->icon('heroicon-o-calendar-days')
                ->color('success'),
            Stat::make('Negocios bloqueados', $blockedBusinesses)
                ->description('Superaron límite gratuito')
                ->icon('heroicon-o-lock-closed')
                ->color($blockedBusinesses > 0 ? 'warning' : 'gray'),
            Stat::make('Pagos del mes', $paidThisMonth)
                ->description('$ '.number_format($revenueThisMonth).' COP')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
