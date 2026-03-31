<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_business');
    }

    public function view(User $user, Business $business): bool
    {
        if (! $user->can('view_business')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $business->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_business');
    }

    public function update(User $user, Business $business): bool
    {
        if (! $user->can('update_business')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $business->id;
    }

    public function delete(User $user, Business $business): bool
    {
        if (! $user->can('delete_business')) {
            return false;
        }

        return $user->hasRole('super_admin');
    }

    public function restore(User $user, Business $business): bool
    {
        if (! $user->can('restore_business')) {
            return false;
        }

        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Business $business): bool
    {
        if (! $user->can('force_delete_business')) {
            return false;
        }

        return $user->hasRole('super_admin');
    }
}
