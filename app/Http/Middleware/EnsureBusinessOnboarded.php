<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        if ($user->business_id !== null) {
            return $next($request);
        }

        $onboardingPath = filament()->getPanel('admin')->getPath().'/onboarding';

        if ($request->is($onboardingPath, 'livewire/*', '*/logout')) {
            return $next($request);
        }

        return redirect()->to('/'.$onboardingPath);
    }
}
