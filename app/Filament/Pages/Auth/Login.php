<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction()
                ->label('Iniciar sesión'),
        ];
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString('La forma inteligente de gestionar tu agenda');
    }

    public function getHeading(): string|Htmlable
    {
        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => '¡Buenos días!',
            $hour < 18 => '¡Buenas tardes!',
            default => '¡Buenas noches!',
        };

        return new HtmlString('<img src="/images/logo-light.png" alt="Citora" style="height:48px;margin:0 auto 8px" onerror="this.style.display=\'none\'"><br>'.$greeting);
    }
}
