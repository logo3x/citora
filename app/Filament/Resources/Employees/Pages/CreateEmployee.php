<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Jobs\SendWhatsAppNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $employee = auth()->user()->business->employees()->create($data);

        if ($employee->phone) {
            SendWhatsAppNotification::dispatch('employee.registered', null, [
                'phone' => $employee->phone,
                'name' => $employee->name,
                'business_name' => auth()->user()->business->name,
            ]);
        }

        return $employee;
    }
}
