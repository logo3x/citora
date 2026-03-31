<?php

namespace App\Actions\Employee;

use App\Models\Employee;

class UpdateEmployee
{
    /**
     * @param  array{position?: string|null, is_active?: bool}  $data
     */
    public function handle(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee->refresh();
    }
}
