<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_service');
    }

    public function view(User $user, Service $service): bool
    {
        if (! $user->can('view_service')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $service->business_id;
    }

    public function create(User $user): bool
    {
        if (! $user->can('create_service')) {
            return false;
        }

        return $user->hasRole('super_admin') || $user->business_id !== null;
    }

    public function update(User $user, Service $service): bool
    {
        if (! $user->can('update_service')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $service->business_id;
    }

    public function delete(User $user, Service $service): bool
    {
        if (! $user->can('delete_service')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $service->business_id;
    }

    public function restore(User $user, Service $service): bool
    {
        if (! $user->can('restore_service')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $service->business_id;
    }

    public function forceDelete(User $user, Service $service): bool
    {
        if (! $user->can('force_delete_service')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->business_id !== null
            && $user->business_id === $service->business_id;
    }
}
