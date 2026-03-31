<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('starts_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sin asignar'),
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label('Fin')
                    ->dateTime('H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (AppointmentStatus $state): string => match ($state) {
                        AppointmentStatus::Pending => 'warning',
                        AppointmentStatus::Confirmed => 'info',
                        AppointmentStatus::Completed => 'success',
                        AppointmentStatus::Cancelled => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(AppointmentStatus::class),
                SelectFilter::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name'),
                SelectFilter::make('employee_id')
                    ->label('Empleado')
                    ->relationship('employee', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
