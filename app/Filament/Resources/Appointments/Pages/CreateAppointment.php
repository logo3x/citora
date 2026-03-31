<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use App\Jobs\SendWhatsAppNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data = AppointmentForm::prepareData($data);

        AppointmentForm::validateNoOverlap($data);

        $appointment = auth()->user()->business->appointments()->create($data);

        SendWhatsAppNotification::dispatch('appointment.created', $appointment);

        return $appointment;
    }
}
