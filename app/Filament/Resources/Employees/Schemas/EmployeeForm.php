<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\BusinessSchedule;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del empleado')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('position')
                            ->label('Cargo')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Teléfono / WhatsApp')
                            ->tel()
                            ->maxLength(20)
                            ->helperText('Para recibir notificaciones de citas'),
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->label('Foto')
                            ->collection('photo')
                            ->disk('public')
                            ->image()
                            ->imageEditor(),
                        Select::make('services')
                            ->label('Servicios que realiza')
                            ->multiple()
                            ->relationship('services', 'name', fn ($query) => $query->where('business_id', auth()->user()->business_id))
                            ->preload(),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Horario de trabajo')
                    ->description('Por defecto se usa el horario del negocio. Puedes personalizarlo.')
                    ->schema([
                        Repeater::make('schedules')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Select::make('day_of_week')
                                    ->label('Día')
                                    ->options([
                                        1 => 'Lunes',
                                        2 => 'Martes',
                                        3 => 'Miércoles',
                                        4 => 'Jueves',
                                        5 => 'Viernes',
                                        6 => 'Sábado',
                                        0 => 'Domingo',
                                    ])
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->label('Entrada')
                                    ->required()
                                    ->seconds(false),
                                TimePicker::make('end_time')
                                    ->label('Salida')
                                    ->required()
                                    ->seconds(false),
                                Toggle::make('is_active')
                                    ->label('Activo')
                                    ->default(true),
                            ])
                            ->columns(4)
                            ->default(fn () => static::getDefaultSchedules())
                            ->addActionLabel('Agregar día')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function getDefaultSchedules(): array
    {
        $business = auth()->user()->business;

        if (! $business) {
            return [];
        }

        return $business->schedules()
            ->where('is_active', true)
            ->orderByRaw('CASE WHEN day_of_week = 0 THEN 7 ELSE day_of_week END')
            ->get()
            ->map(fn (BusinessSchedule $schedule) => [
                'day_of_week' => $schedule->day_of_week,
                'start_time' => $schedule->open_time,
                'end_time' => $schedule->close_time,
                'is_active' => true,
            ])
            ->all();
    }
}
