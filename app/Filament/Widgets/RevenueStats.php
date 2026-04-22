<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class RevenueStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ingresos de citas completadas';

    protected function getStats(): array
    {
        return [
            $this->buildStat('Hoy', today(), today()->endOfDay(), 'heroicon-o-calendar'),
            $this->buildStat('Esta semana', now()->startOfWeek(), now()->endOfWeek(), 'heroicon-o-calendar-days'),
            $this->buildStat('Este mes', now()->startOfMonth(), now()->endOfMonth(), 'heroicon-o-banknotes'),
        ];
    }

    private function buildStat(string $label, Carbon $from, Carbon $to, string $icon): Stat
    {
        $revenue = $this->revenueBetween($from, $to);
        $count = $this->countBetween($from, $to);

        return Stat::make($label, '$'.number_format($revenue, 0, ',', '.'))
            ->description("{$count} ".($count === 1 ? 'cita completada' : 'citas completadas'))
            ->descriptionIcon($icon)
            ->color($revenue > 0 ? 'success' : 'gray');
    }

    private function revenueBetween(Carbon $from, Carbon $to): int
    {
        return (int) $this->baseQuery()
            ->whereBetween('starts_at', [$from, $to])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');
    }

    private function countBetween(Carbon $from, Carbon $to): int
    {
        return $this->baseQuery()
            ->whereBetween('starts_at', [$from, $to])
            ->count();
    }

    private function baseQuery(): Builder
    {
        $user = auth()->user();

        $query = Appointment::query()
            ->where('appointments.status', AppointmentStatus::Completed);

        if (! $user->hasRole('super_admin')) {
            $query->where('appointments.business_id', $user->business_id);
        }

        return $query;
    }
}
