<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->employees()->count() === 0) {
            Notification::make()
                ->warning()
                ->title('Este servicio no tiene empleados asignados')
                ->body('No aparecerá disponible para reservar hasta que asignes al menos un empleado que pueda realizarlo.')
                ->persistent()
                ->send();
        }
    }
}
