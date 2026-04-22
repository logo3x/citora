<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingAppointments extends TableWidget
{
    protected static ?string $heading = 'Próximas citas (desde mañana)';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $user = auth()->user();

                $query = Appointment::with(['service', 'employee', 'customer'])
                    ->whereDate('starts_at', '>=', today()->addDay())
                    ->where('status', '!=', AppointmentStatus::Cancelled)
                    ->orderBy('starts_at');

                if (! $user->hasRole('super_admin')) {
                    $query->where('business_id', $user->business_id);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('starts_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Servicio'),
                TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->placeholder('Sin asignar'),
                TextColumn::make('customer.name')
                    ->label('Cliente'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (AppointmentStatus $state): string => $state->label())
                    ->color(fn (AppointmentStatus $state): string => $state->color()),
            ])
            ->defaultPaginationPageOption(5);
    }
}
