<?php

namespace App\Actions\Employee;

use App\Models\Business;
use App\Models\Employee;

class CreateEmployee
{
    /**
     * @param  array{user_id: int, position?: string|null}  $data
     */
    public function handle(Business $business, array $data): Employee
    {
        return $business->employees()->create($data);
    }
}
