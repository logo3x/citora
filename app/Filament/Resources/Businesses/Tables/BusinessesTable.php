<?php

namespace App\Filament\Resources\Businesses\Tables;

use App\Models\Business;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BusinessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->label('')
                    ->collection('logo')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Business $record) => $record->slug),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('services_count')
                    ->label('Servicios')
                    ->counts('services')
                    ->sortable(),
                TextColumn::make('employees_count')
                    ->label('Empleados')
                    ->counts('employees')
                    ->sortable(),
                TextColumn::make('appointments_count')
                    ->label('Citas/mes')
                    ->getStateUsing(fn (Business $record) => $record->getMonthlyAppointmentCount().' / '.$record->monthly_appointment_limit)
                    ->color(fn (Business $record) => $record->hasReachedMonthlyLimit() ? 'danger' : 'success'),
                TextColumn::make('plan_status')
                    ->label('Plan')
                    ->getStateUsing(function (Business $record): string {
                        if ($record->isUnlockedForPeriod()) {
                            return 'Ilimitado';
                        }
                        if ($record->hasReachedMonthlyLimit()) {
                            return 'Bloqueado';
                        }

                        return 'Gratuito';
                    })
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Ilimitado' => 'success',
                        'Bloqueado' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registro')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
