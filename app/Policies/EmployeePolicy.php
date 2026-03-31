<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_employee');
    }

    public function view(User $user, Employee $employee): bool
    {
        if (! $user->can('view_employee')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $employee->business_id;
    }

    public function create(User $user): bool
    {
        if (! $user->can('create_employee')) {
            return false;
        }

        return $user->hasRole('super_admin') || $user->business_id !== null;
    }

    public function update(User $user, Employee $employee): bool
    {
        if (! $user->can('update_employee')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $employee->business_id;
    }

    public function delete(User $user, Employee $employee): bool
    {
        if (! $user->can('delete_employee')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $employee->business_id;
    }

    public function restore(User $user, Employee $employee): bool
    {
        if (! $user->can('restore_employee')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $employee->business_id;
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        if (! $user->can('force_delete_employee')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $employee->business_id;
    }
}
