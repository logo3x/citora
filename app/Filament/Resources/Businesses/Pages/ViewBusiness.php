<?php

namespace App\Filament\Resources\Businesses\Pages;

use App\Filament\Resources\Businesses\BusinessResource;
use App\Models\Business;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewBusiness extends ViewRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        $publicUrl = rtrim(config('app.url'), '/').'/'.$this->record->slug;

        return [
            Action::make('view_public')
                ->label('Ver página')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url($publicUrl, shouldOpenInNewTab: true),
            Action::make('copy_link')
                ->label('Copiar enlace')
                ->icon('heroicon-o-clipboard-document')
                ->color('gray')
                ->action(function () use ($publicUrl): void {
                    $this->js("navigator.clipboard.writeText('{$publicUrl}')");

                    Notification::make()
                        ->success()
                        ->title('¡Enlace copiado!')
                        ->body($publicUrl)
                        ->send();
                }),
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->columnSpanFull()->schema([

                Section::make('Información general')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('logo')
                            ->label('Logo')
                            ->collection('logo')
                            ->circular(),
                        TextEntry::make('name')->label('Nombre'),
                        TextEntry::make('slogan')->label('Eslogan')->placeholder('—'),
                        TextEntry::make('slug')
                            ->label('URL pública')
                            ->url(fn (Business $record) => rtrim(config('app.url'), '/').'/'.$record->slug, shouldOpenInNewTab: true)
                            ->color('primary'),
                        TextEntry::make('email')->label('Correo')->placeholder('—'),
                        TextEntry::make('phone')->label('Teléfono')->placeholder('—'),
                        TextEntry::make('address')->label('Dirección')->placeholder('—'),
                        TextEntry::make('description')->label('Descripción')->placeholder('—')->columnSpanFull(),
                        TextEntry::make('is_active')
                            ->label('Estado')
                            ->badge()
                            ->formatStateUsing(fn (bool $state) => $state ? 'Activo' : 'Inactivo')
                            ->color(fn (bool $state) => $state ? 'success' : 'danger'),
                        TextEntry::make('created_at')->label('Registrado')->dateTime('d/m/Y H:i'),
                        TextEntry::make('plan')
                            ->label('Plan')
                            ->getStateUsing(function (Business $record): string {
                                if ($record->isUnlockedForPeriod()) {
                                    return 'Ilimitado (pagado)';
                                }
                                if ($record->hasReachedMonthlyLimit()) {
                                    return 'Bloqueado';
                                }

                                return 'Gratuito';
                            })
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'Ilimitado (pagado)' => 'success',
                                'Bloqueado' => 'danger',
                                default => 'info',
                            }),
                        TextEntry::make('usage')
                            ->label('Citas del mes')
                            ->getStateUsing(fn (Business $record) => $record->getMonthlyAppointmentCount().' / '.$record->monthly_appointment_limit),
                        TextEntry::make('payments_total')
                            ->label('Pagos')
                            ->getStateUsing(fn (Business $record) => $record->payments()->where('status', 'approved')->count()),
                    ])
                    ->columns(2),

                Section::make('Detalle')
                    ->schema([

                        Section::make('Servicios ('.$this->record->services()->count().')')
                            ->schema([
                                RepeatableEntry::make('services')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('name')->label('Servicio'),
                                        TextEntry::make('duration_minutes')->label('Duración')->suffix(' min'),
                                        TextEntry::make('price')->label('Precio')->money('COP'),
                                        TextEntry::make('is_active')
                                            ->label('Estado')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Activo' : 'Inactivo')
                                            ->color(fn (bool $state) => $state ? 'success' : 'danger'),
                                    ])
                                    ->columns(4),
                            ])
                            ->collapsible()
                            ->collapsed(),

                        Section::make('Empleados ('.$this->record->employees()->count().')')
                            ->schema([
                                RepeatableEntry::make('employees')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('name')->label('Nombre'),
                                        TextEntry::make('position')->label('Cargo')->placeholder('—'),
                                        TextEntry::make('phone')->label('Teléfono')->placeholder('—'),
                                        TextEntry::make('is_active')
                                            ->label('Estado')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Activo' : 'Inactivo')
                                            ->color(fn (bool $state) => $state ? 'success' : 'danger'),
                                    ])
                                    ->columns(4),
                            ])
                            ->collapsible()
                            ->collapsed(),

                        Section::make('Horarios de atención')
                            ->schema([
                                RepeatableEntry::make('schedules')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('day_of_week')
                                            ->label('Día')
                                            ->formatStateUsing(fn (int $state) => ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'][$state] ?? ''),
                                        TextEntry::make('open_time')->label('Apertura'),
                                        TextEntry::make('close_time')->label('Cierre'),
                                        TextEntry::make('is_active')
                                            ->label('Estado')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Abierto' : 'Cerrado')
                                            ->color(fn (bool $state) => $state ? 'success' : 'danger'),
                                    ])
                                    ->columns(4),
                            ])
                            ->collapsible()
                            ->collapsed(),

                    ])
                    ->hiddenLabel(),

            ]), // end Grid
        ]);
    }
}
