<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use App\Jobs\SendWhatsAppNotification;
use App\Models\Business;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->notifyBusinessesWithoutEmployeesAction(),
            CreateAction::make(),
        ];
    }

    private function notifyBusinessesWithoutEmployeesAction(): Action
    {
        return Action::make('notifyBusinessesWithoutEmployees')
            ->label('Avisar por WhatsApp')
            ->icon(Heroicon::OutlinedChatBubbleLeftEllipsis)
            ->color('warning')
            ->visible(fn (): bool => auth()->user()->hasRole('super_admin'))
            ->requiresConfirmation()
            ->modalHeading('Avisar a negocios con servicios sin empleado')
            ->modalDescription(function (): string {
                $businesses = $this->businessesWithServicesWithoutEmployee();
                $withPhone = $businesses->filter(fn (Business $business): bool => $this->contactPhoneFor($business) !== null)->count();

                if ($businesses->isEmpty()) {
                    return 'No hay negocios activos con servicios sin empleado. No se enviará ningún mensaje.';
                }

                return "Se enviará un WhatsApp a {$withPhone} de {$businesses->count()} negocio(s) activos con servicios activos sin empleado. "
                    .'Los negocios sin teléfono registrado no recibirán el aviso.';
            })
            ->modalSubmitActionLabel('Enviar avisos')
            ->action(function (): void {
                $sent = [];
                $withoutPhone = [];

                foreach ($this->businessesWithServicesWithoutEmployee() as $business) {
                    $phone = $this->contactPhoneFor($business);

                    if ($phone === null) {
                        $withoutPhone[] = $business->name;

                        continue;
                    }

                    SendWhatsAppNotification::dispatch('business.services_without_employee', null, [
                        'phone' => $phone,
                        'business_name' => $business->name,
                        'service_names' => $business->services->pluck('name')->all(),
                    ]);

                    $sent[] = $business->name;
                }

                $body = count($sent) > 0
                    ? 'Avisados: '.implode(', ', $sent).'.'
                    : 'No se envió ningún aviso.';

                if (count($withoutPhone) > 0) {
                    $body .= ' Sin teléfono (no avisados): '.implode(', ', $withoutPhone).'.';
                }

                Notification::make()
                    ->title(count($sent).' aviso(s) en cola de envío')
                    ->body($body)
                    ->color(count($withoutPhone) > 0 ? 'warning' : 'success')
                    ->persistent()
                    ->send();
            });
    }

    /**
     * Negocios activos con al menos un servicio activo sin empleados activos.
     * Cada negocio trae cargados solo esos servicios.
     *
     * @return Collection<int, Business>
     */
    private function businessesWithServicesWithoutEmployee(): Collection
    {
        $servicesWithoutEmployee = fn (Builder $services): Builder => $services
            ->where('is_active', true)
            ->whereDoesntHave('employees', fn (Builder $employees): Builder => $employees->where('is_active', true));

        return Business::query()
            ->where('is_active', true)
            ->whereHas('services', $servicesWithoutEmployee)
            ->with([
                'services' => $servicesWithoutEmployee,
                'users:id,business_id,phone',
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * Teléfono del negocio o, si no tiene, el del primer usuario del negocio que tenga uno.
     */
    private function contactPhoneFor(Business $business): ?string
    {
        if (filled($business->phone)) {
            return $business->phone;
        }

        return $business->users->first(fn ($user): bool => filled($user->phone))?->phone;
    }
}
