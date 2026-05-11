<?php

namespace App\Filament\Resources\EmailCampaigns\Pages;

use App\Filament\Resources\EmailCampaigns\EmailCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailCampaign extends CreateRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['status'] = isset($data['scheduled_at']) && $data['scheduled_at'] ? 'scheduled' : 'draft';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
