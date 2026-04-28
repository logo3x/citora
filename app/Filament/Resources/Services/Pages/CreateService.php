<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $employeeIds = $data['employees'] ?? [];
        unset($data['employees']);

        $service = auth()->user()->business->services()->create($data);

        if (! empty($employeeIds)) {
            $service->employees()->sync($employeeIds);
        }

        return $service;
    }

    protected function afterCreate(): void
    {
        if ($this->record->employees()->count() === 0) {
            Notification::make()
                ->warning()
                ->title('Servicio creado, pero sin empleados asignados')
                ->body('Este servicio no aparecerá disponible para reservar hasta que asignes al menos un empleado que pueda realizarlo.')
                ->persistent()
                ->send();
        }
    }
}
