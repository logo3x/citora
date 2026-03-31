<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class EmployeeService
{
    /**
     * @return Builder<Employee>
     */
    public function getQueryForUser(User $user): Builder
    {
        $query = Employee::query();

        if (! $user->hasRole('super_admin')) {
            $query->where('business_id', $user->business_id);
        }

        return $query;
    }
}
