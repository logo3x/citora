<?php

namespace App\Filament\Resources\EmailCampaigns\Pages;

use App\Filament\Resources\EmailCampaigns\EmailCampaignResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmailCampaign extends EditRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si tenía fecha y ahora la quitan -> vuelve a borrador.
        // Si no tenía y le ponen una -> queda programada.
        $data['status'] = isset($data['scheduled_at']) && $data['scheduled_at'] ? 'scheduled' : 'draft';

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
