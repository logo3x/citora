<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('avatar')
                    ->label('')
                    ->formatStateUsing(fn ($state, $record) => '<img src="'.e($state ?: 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=f59e0b&color=fff&size=40').'" referrerpolicy="no-referrer" class="rounded-full" style="width:40px;height:40px;object-fit:cover" />')
                    ->html(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('business.name')
                    ->label('Negocio')
                    ->placeholder('Sin negocio')
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(', '),
                TextColumn::make('google_id')
                    ->label('Google')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Sí' : 'No')
                    ->badge()
                    ->color(fn (?string $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->label('Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('roles')
                    ->label('Rol')
                    ->relationship('roles', 'name'),
                SelectFilter::make('business_id')
                    ->label('Negocio')
                    ->relationship('business', 'name'),
            ])
            ->recordActions([
                Impersonate::make()
                    ->redirectTo(fn () => filament()->getUrl()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
