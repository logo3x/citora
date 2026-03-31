<?php

namespace App\Actions\Business;

use App\Models\Business;
use Illuminate\Support\Str;

class UpdateBusiness
{
    /**
     * @param  array{name?: string, email?: string|null, phone?: string|null, address?: string|null, is_active?: bool}  $data
     */
    public function handle(Business $business, array $data): Business
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $business->update($data);

        return $business->refresh();
    }
}
