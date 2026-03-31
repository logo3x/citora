<?php

namespace App\Services;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ServiceService
{
    /**
     * @return Builder<Service>
     */
    public function getQueryForUser(User $user): Builder
    {
        $query = Service::query();

        if (! $user->hasRole('super_admin')) {
            $query->where('business_id', $user->business_id);
        }

        return $query;
    }
}
