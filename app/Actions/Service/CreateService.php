<?php

namespace App\Actions\Service;

use App\Models\Business;
use App\Models\Service;

class CreateService
{
    /**
     * @param  array{name: string, description?: string|null, duration_minutes?: int, price?: int}  $data
     */
    public function handle(Business $business, array $data): Service
    {
        return $business->services()->create($data);
    }
}
