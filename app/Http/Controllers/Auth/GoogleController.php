<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (request()->has('redirect_to')) {
            session()->put('booking_redirect', request()->input('redirect_to'));
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                $user->assignRole('customer');
            }
        }

        Auth::login($user, remember: true);

        session()->regenerate();

        $redirectTo = session()->pull('booking_redirect');

        if ($redirectTo && ! str_starts_with($redirectTo, '/') && ! str_starts_with($redirectTo, config('app.url'))) {
            $redirectTo = null;
        }

        return redirect()->to($redirectTo ?? filament()->getPanel('admin')->getUrl());
    }
}
