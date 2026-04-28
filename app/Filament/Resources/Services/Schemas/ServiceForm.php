<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del servicio')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Imagen')
                            ->collection('image')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048),
                        Select::make('duration_minutes')
                            ->label('Duración')
                            ->options([
                                15 => '15 minutos',
                                30 => '30 minutos',
                                45 => '45 minutos',
                                60 => '1 hora',
                                90 => '1 hora 30 min',
                                120 => '2 horas',
                            ])
                            ->required()
                            ->default(30),
                        TextInput::make('price')
                            ->label('Precio')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                        Select::make('employees')
                            ->label('Empleados que pueden realizar este servicio')
                            ->multiple()
                            ->relationship('employees', 'name', fn ($query) => $query->where('business_id', auth()->user()->business_id))
                            ->preload()
                            ->helperText('⚠️ Si no asignas al menos un empleado, este servicio no aparecerá disponible para reservar.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
