<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_appointment');
    }

    public function view(User $user, Appointment $appointment): bool
    {
        if (! $user->can('view_appointment')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole('customer')) {
            return $appointment->customer_id === $user->id;
        }

        return $user->business_id !== null
            && $user->business_id === $appointment->business_id;
    }

    public function create(User $user): bool
    {
        if (! $user->can('create_appointment')) {
            return false;
        }

        return $user->hasRole(['super_admin', 'customer']) || $user->business_id !== null;
    }

    public function update(User $user, Appointment $appointment): bool
    {
        if (! $user->can('update_appointment')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $appointment->business_id;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        if (! $user->can('delete_appointment')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $appointment->business_id;
    }

    public function restore(User $user, Appointment $appointment): bool
    {
        if (! $user->can('restore_appointment')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $appointment->business_id;
    }

    public function forceDelete(User $user, Appointment $appointment): bool
    {
        if (! $user->can('force_delete_appointment')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $appointment->business_id;
    }
}
