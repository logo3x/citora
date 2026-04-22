<?php

namespace App\Filament\Pages;

use App\Enums\AppointmentStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AppointmentsCalendar extends Page
{
    protected string $view = 'filament.pages.appointments-calendar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Calendario';

    protected static ?string $title = 'Calendario de citas';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'calendario';

    public function getHeading(): string
    {
        return 'Calendario de citas';
    }

    public function getSubheading(): ?string
    {
        return now()->translatedFormat('F \\d\\e Y');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('list')
                ->label('Ver lista')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(AppointmentResource::getUrl('index')),
            CreateAction::make()
                ->url(AppointmentResource::getUrl('create'))
                ->icon('heroicon-o-plus')
                ->label('Nueva cita'),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEvents(?string $start = null, ?string $end = null): array
    {
        $user = auth()->user();

        $query = Appointment::with(['service', 'employee', 'customer'])
            ->where('status', '!=', AppointmentStatus::Cancelled);

        if (! $user->hasRole('super_admin')) {
            $query->where('business_id', $user->business_id);
        }

        if ($start) {
            $query->where('starts_at', '>=', $start);
        }

        if ($end) {
            $query->where('starts_at', '<=', $end);
        }

        return $query->get()->map(function (Appointment $appointment): array {
            $colorByStatus = match ($appointment->status) {
                AppointmentStatus::Pending => '#F59E0B',
                AppointmentStatus::Confirmed => '#2563EB',
                AppointmentStatus::Completed => '#059669',
                AppointmentStatus::Cancelled => '#9CA3AF',
            };

            return [
                'id' => $appointment->id,
                'title' => sprintf(
                    '%s — %s',
                    $appointment->customer->name ?? 'Sin cliente',
                    $appointment->service->name ?? '—',
                ),
                'start' => $appointment->starts_at->toIso8601String(),
                'end' => $appointment->ends_at?->toIso8601String(),
                'backgroundColor' => $colorByStatus,
                'borderColor' => $colorByStatus,
                'extendedProps' => [
                    'employee' => $appointment->employee?->name,
                    'status' => $appointment->status->value,
                    'price' => $appointment->service->price ?? 0,
                    'editUrl' => AppointmentResource::getUrl('edit', ['record' => $appointment->id]),
                ],
            ];
        })->values()->all();
    }
}
