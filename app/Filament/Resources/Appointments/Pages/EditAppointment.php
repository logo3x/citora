<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $startsAt = $this->record->starts_at;

        if ($startsAt) {
            $data['appointment_date'] = $startsAt->toDateString();
            $data['time_slot'] = $startsAt->format('H:i');
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $originalDate = $this->record->starts_at ? Carbon::parse($this->record->starts_at)->toDateString() : null;
        $originalTime = $this->record->starts_at ? Carbon::parse($this->record->starts_at)->format('H:i') : null;

        $newDate = $data['appointment_date'] ?? $originalDate;
        $newTime = $data['time_slot'] ?? $originalTime;

        $dateChanged = $newDate !== $originalDate || $newTime !== $originalTime;

        if ($dateChanged && $newDate && $newTime) {
            $data = AppointmentForm::prepareData($data);
            AppointmentForm::validateNoOverlap($data, $this->record);
        } else {
            $data['starts_at'] = $this->record->starts_at;
            $data['ends_at'] = $this->record->ends_at;
            unset($data['appointment_date'], $data['time_slot']);
        }

        return $data;
    }
}
