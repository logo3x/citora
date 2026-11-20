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
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('business.name')
                    ->label('Negocio')
                    ->searchable()
                    ->sortable()
                    ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
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
                    ->color(fn (Service $record): string => $record->employees->where('is_active', true)->isEmpty() ? 'danger' : 'success')
                    ->tooltip(fn (Service $record): ?string => $record->employees->where('is_active', true)->isEmpty()
                        ? 'Este servicio no aparece en la página pública. Edítalo y asigna al menos un empleado activo.'
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
                    ->label('Sin empleados activos (no reservables)')
                    ->query(fn (Builder $query): Builder => $query->whereDoesntHave(
                        'employees',
                        fn (Builder $employees): Builder => $employees->where('is_active', true),
                    )),
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
