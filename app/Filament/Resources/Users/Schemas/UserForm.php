<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Business;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del usuario')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                        TextInput::make('google_id')
                            ->label('Google ID')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn('edit'),
                    ])
                    ->columns(2),

                Section::make('Negocio y roles')
                    ->schema([
                        Select::make('business_id')
                            ->label('Negocio')
                            ->options(fn () => Business::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                        Select::make('roles')
                            ->label('Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->options(fn () => Role::pluck('name', 'id'))
                            ->preload(),
                    ])
                    ->columns(2),
            ]);
    }
}
