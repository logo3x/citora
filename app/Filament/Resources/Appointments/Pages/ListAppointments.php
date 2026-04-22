<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Pages\AppointmentsCalendar;
use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('calendar')
                ->label('Calendario')
                ->icon('heroicon-o-calendar-days')
                ->color('gray')
                ->url(AppointmentsCalendar::getUrl()),

            ActionGroup::make([
                Action::make('export_current_month')
                    ->label('Mes actual')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(route('appointments.export.csv', ['month' => now()->format('Y-m')]), shouldOpenInNewTab: true),

                Action::make('export_previous_month')
                    ->label('Mes anterior')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(route('appointments.export.csv', ['month' => now()->subMonth()->format('Y-m')]), shouldOpenInNewTab: true),

                Action::make('export_custom')
                    ->label('Otro mes…')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('month')
                            ->label('Mes (YYYY-MM)')
                            ->placeholder('2026-04')
                            ->required()
                            ->regex('/^\d{4}-\d{2}$/')
                            ->default(now()->format('Y-m')),
                    ])
                    ->action(function (array $data) {
                        return redirect()->away(route('appointments.export.csv', ['month' => $data['month']]));
                    }),
            ])
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->button(),

            CreateAction::make(),
        ];
    }
}
