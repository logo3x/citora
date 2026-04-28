<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PushNotifications extends Page
{
    protected string $view = 'filament.pages.push-notifications';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    protected static ?string $navigationLabel = 'Notificaciones push';

    protected static ?string $title = 'Notificaciones push';

    protected static ?int $navigationSort = 90;

    protected static ?string $slug = 'notificaciones-push';

    public function getHeading(): string
    {
        return 'Notificaciones push del navegador';
    }

    public function getSubheading(): ?string
    {
        return 'Activa para recibir alertas instantáneas cuando llegue una nueva cita, aunque tengas Citora cerrado.';
    }
}
