<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AppointmentsChart extends ChartWidget
{
    protected ?string $heading = 'Citas de los últimos 7 días';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $days = collect(range(6, 0))->map(fn (int $daysAgo) => Carbon::today()->subDays($daysAgo));

        $counts = $days->map(function (Carbon $date) use ($user, $businessId): int {
            $query = Appointment::whereDate('starts_at', $date);

            if (! $user->hasRole('super_admin')) {
                $query->where('business_id', $businessId);
            }

            return $query->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Citas',
                    'data' => $counts->all(),
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'borderColor' => 'rgb(245, 158, 11)',
                ],
            ],
            'labels' => $days->map(fn (Carbon $date) => $date->translatedFormat('D d'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
