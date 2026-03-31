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
            $this->getAuthenticateFormAction(),
        ];
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString('La forma inteligente de gestionar tu agenda');
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('<img src="/images/logo-light.png" alt="Citora" class="h-12 mx-auto mb-2" onerror="this.outerHTML=\'<span class=text-2xl>Citora</span>\'">');
    }
}
