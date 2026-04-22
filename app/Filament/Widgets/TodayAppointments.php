<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
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
                            return 'success';
                        }

                        if ($starts->lte($now)) {
                            return 'gray';
                        }

                        if ($starts->diffInMinutes($now) <= 60) {
                            return 'warning';
                        }

                        return 'info';
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
                    ->formatStateUsing(fn (AppointmentStatus $state): string => $state->label())
                    ->color(fn (AppointmentStatus $state): string => $state->color()),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('edit')
                        ->label('Ver / editar')
                        ->icon('heroicon-m-pencil-square')
                        ->url(fn (Appointment $record): string => AppointmentResource::getUrl('edit', ['record' => $record])),

                    Action::make('confirm')
                        ->label('Confirmar')
                        ->icon('heroicon-m-check-badge')
                        ->color('info')
                        ->visible(fn (Appointment $record): bool => $record->status === AppointmentStatus::Pending)
                        ->requiresConfirmation()
                        ->modalHeading('Confirmar cita')
                        ->modalDescription(fn (Appointment $record) => "Marcar como confirmada la cita de {$record->customer?->name} a las {$record->starts_at->format('g:i A')}.")
                        ->action(function (Appointment $record): void {
                            $record->update(['status' => AppointmentStatus::Confirmed]);
                            Notification::make()->success()->title('Cita confirmada')->send();
                        }),

                    Action::make('complete')
                        ->label('Completar')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (Appointment $record): bool => ! $record->status->isFinal())
                        ->requiresConfirmation()
                        ->modalHeading('Completar cita')
                        ->modalDescription('Marca la cita como completada. Se notificará al cliente por WhatsApp y correo.')
                        ->action(function (Appointment $record): void {
                            $record->update(['status' => AppointmentStatus::Completed]);
                            Notification::make()->success()->title('Cita completada')->send();
                        }),

                    Action::make('late')
                        ->label('Llegó tarde')
                        ->icon('heroicon-m-clock')
                        ->color('warning')
                        ->visible(fn (Appointment $record): bool => ! $record->status->isFinal())
                        ->requiresConfirmation()
                        ->modalHeading('Marcar como "llegó tarde"')
                        ->action(function (Appointment $record): void {
                            $record->update(['status' => AppointmentStatus::LateArrival]);
                            Notification::make()->success()->title('Marcada como "llegó tarde"')->send();
                        }),

                    Action::make('no_show')
                        ->label('No llegó')
                        ->icon('heroicon-m-user-minus')
                        ->color('gray')
                        ->visible(fn (Appointment $record): bool => ! $record->status->isFinal())
                        ->requiresConfirmation()
                        ->modalHeading('Marcar como "no llegó"')
                        ->modalDescription('El cliente no asistió. Quedará registrado para estadísticas.')
                        ->action(function (Appointment $record): void {
                            $record->update(['status' => AppointmentStatus::NoShow]);
                            Notification::make()->success()->title('Marcada como "no llegó"')->send();
                        }),

                    Action::make('cancel')
                        ->label('Cancelar')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->visible(fn (Appointment $record): bool => ! $record->status->isFinal())
                        ->requiresConfirmation()
                        ->modalHeading('Cancelar cita')
                        ->modalDescription('Se notificará al cliente por WhatsApp y correo.')
                        ->action(function (Appointment $record): void {
                            $record->update(['status' => AppointmentStatus::Cancelled]);
                            Notification::make()->success()->title('Cita cancelada')->send();
                        }),
                ])
                    ->label('Acciones')
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->size('sm')
                    ->color('gray')
                    ->button(),
            ])
            ->paginated(false);
    }
}
