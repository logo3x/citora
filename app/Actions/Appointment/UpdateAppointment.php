<?php

namespace App\Actions\Appointment;

use App\Models\Appointment;

class UpdateAppointment
{
    /**
     * @param  array{service_id?: int, employee_id?: int|null, starts_at?: string, ends_at?: string, status?: string, notes?: string|null}  $data
     */
    public function handle(Appointment $appointment, array $data): Appointment
    {
        $appointment->update($data);

        return $appointment->refresh();
    }
}
