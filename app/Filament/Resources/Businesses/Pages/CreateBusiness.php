<?php

namespace App\Filament\Resources\Businesses\Pages;

use App\Filament\Resources\Businesses\BusinessResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $user = auth()->user();

        if (! $user->hasRole('super_admin') && $user->business_id === null) {
            $user->business_id = $this->record->id;
            $user->save();

            if ($user->hasRole('customer')) {
                $user->removeRole('customer');
            }

            $user->assignRole('business_owner');
        }
    }
}
