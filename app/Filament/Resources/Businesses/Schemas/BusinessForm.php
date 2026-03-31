<?php

namespace App\Filament\Resources\Businesses\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusinessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del negocio')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('URL pública')
                            ->prefix('citora.com/')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(20),
                        Textarea::make('address')
                            ->label('Dirección')
                            ->rows(2)
                            ->maxLength(500),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->visibleOn('edit'),
                    ])
                    ->columns(2),
            ]);
    }
}
