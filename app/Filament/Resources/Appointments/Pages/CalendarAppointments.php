<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Enums\AppointmentStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Page;

class CalendarAppointments extends Page
{
    protected static string $resource = AppointmentResource::class;

    protected string $view = 'filament.resources.appointments.pages.calendar-appointments';

    protected static ?string $title = 'Calendario';

    public function getBreadcrumb(): string
    {
        return 'Calendario';
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
                AppointmentStatus::Pending => '#F59E0B',      // amber
                AppointmentStatus::Confirmed => '#2563EB',    // blue
                AppointmentStatus::Completed => '#059669',    // green
                AppointmentStatus::Cancelled => '#9CA3AF',    // gray
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
