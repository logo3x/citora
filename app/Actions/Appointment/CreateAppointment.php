<?php

namespace App\Actions\Appointment;

use App\Models\Appointment;
use App\Models\Business;

class CreateAppointment
{
    /**
     * @param  array{service_id: int, employee_id?: int|null, customer_id: int, starts_at: string, ends_at: string, notes?: string|null}  $data
     */
    public function handle(Business $business, array $data): Appointment
    {
        return $business->appointments()->create($data);
    }
}
