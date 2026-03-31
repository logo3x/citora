<?php

namespace App\Actions\Business;

use App\Models\Business;
use Illuminate\Support\Str;

class CreateBusiness
{
    /**
     * @param  array{name: string, email?: string|null, phone?: string|null, address?: string|null}  $data
     */
    public function handle(array $data): Business
    {
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Business::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $data['slug'] = $slug;

        return Business::create($data);
    }
}
