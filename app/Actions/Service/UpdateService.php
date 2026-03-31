<?php

namespace App\Actions\Service;

use App\Models\Service;

class UpdateService
{
    /**
     * @param  array{name?: string, description?: string|null, duration_minutes?: int, price?: int, is_active?: bool}  $data
     */
    public function handle(Service $service, array $data): Service
    {
        $service->update($data);

        return $service->refresh();
    }
}
