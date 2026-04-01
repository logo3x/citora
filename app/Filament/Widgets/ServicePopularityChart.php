<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ServicePopularityChart extends ChartWidget
{
    protected ?string $heading = 'Servicios más solicitados';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user->hasRole('business_owner') && $user->business_id !== null;
    }

    protected function getData(): array
    {
        $businessId = auth()->user()->business_id;

        $data = Appointment::where('appointments.business_id', $businessId)
            ->whereMonth('appointments.starts_at', now()->month)
            ->whereYear('appointments.starts_at', now()->year)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $colors = ['#D97706', '#0D9488', '#2563EB', '#E11D48', '#7C3AED'];

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->all(),
                    'backgroundColor' => array_slice($colors, 0, $data->count()),
                ],
            ],
            'labels' => $data->pluck('name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
