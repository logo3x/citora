<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AppointmentService
{
    /**
     * @return Builder<Appointment>
     */
    public function getQueryForUser(User $user): Builder
    {
        $query = Appointment::query();

        if ($user->hasRole('super_admin')) {
            return $query;
        }

        if ($user->hasRole('customer')) {
            return $query->where('customer_id', $user->id);
        }

        return $query->where('business_id', $user->business_id);
    }
}
