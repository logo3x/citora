<?php

namespace App\Services;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BusinessService
{
    /**
     * @return Builder<Business>
     */
    public function getQueryForUser(User $user): Builder
    {
        $query = Business::query();

        if (! $user->hasRole('super_admin')) {
            $query->where('id', $user->business_id);
        }

        return $query;
    }
}
