<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TodayAppointments extends TableWidget
{
    protected static ?string $heading = 'Citas de hoy';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $user = auth()->user();

                $query = Appointment::with(['service', 'employee', 'customer'])
                    ->whereDate('starts_at', today())
                    ->where('status', '!=', AppointmentStatus::Cancelled)
                    ->orderBy('starts_at');

                if (! $user->hasRole('super_admin')) {
                    $query->where('business_id', $user->business_id);
                }

                return $query;
            })
            ->emptyStateHeading('No hay citas agendadas para hoy')
            ->emptyStateDescription('Disfruta el día o aprovecha para promocionar tu negocio.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->columns([
                TextColumn::make('starts_at')
                    ->label('Hora')
                    ->formatStateUsing(fn ($state) => $state->format('g:i A'))
                    ->badge()
                    ->color(function ($record) {
                        $now = now();
                        $starts = $record->starts_at;
                        $ends = $record->ends_at;

                        if ($ends && $starts->lte($now) && $ends->gte($now)) {
                            return 'success'; // En curso
                        }

                        if ($starts->lte($now)) {
                            return 'gray'; // Pasada
                        }

                        if ($starts->diffInMinutes($now) <= 60) {
                            return 'warning'; // Próxima (menos de 1h)
                        }

                        return 'info'; // Futura
                    })
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label('Profesional')
                    ->placeholder('Sin asignar'),
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('customer.phone')
                    ->label('Teléfono')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('service.price')
                    ->label('Valor')
                    ->money('COP', locale: 'es_CO')
                    ->alignEnd(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (AppointmentStatus $state): string => match ($state) {
                        AppointmentStatus::Pending => 'Pendiente',
                        AppointmentStatus::Confirmed => 'Confirmada',
                        AppointmentStatus::Completed => 'Completada',
                        AppointmentStatus::Cancelled => 'Cancelada',
                    })
                    ->color(fn (AppointmentStatus $state): string => match ($state) {
                        AppointmentStatus::Pending => 'warning',
                        AppointmentStatus::Confirmed => 'info',
                        AppointmentStatus::Completed => 'success',
                        AppointmentStatus::Cancelled => 'danger',
                    }),
            ])
            ->paginated(false);
    }
}
