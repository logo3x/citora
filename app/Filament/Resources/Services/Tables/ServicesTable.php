<?php

namespace App\Filament\Resources\Services\Tables;

use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('')
                    ->collection('image')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Duración')
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('COP')
                    ->sortable(),
                TextColumn::make('employees.name')
                    ->label('Empleados')
                    ->badge()
                    ->separator(', ')
                    ->placeholder('⚠️ Sin asignar')
                    ->color(fn (Service $record) => $record->employees->isEmpty() ? 'danger' : 'success')
                    ->tooltip(fn (Service $record) => $record->employees->isEmpty()
                        ? 'Este servicio no aparece como reservable. Edítalo y asigna al menos un empleado.'
                        : null),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
                Filter::make('without_employees')
                    ->label('Sin empleados asignados')
                    ->query(fn ($query) => $query->whereDoesntHave('employees')),
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
