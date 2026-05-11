<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

class TouchLastLogin
{
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        // Quiet update — avoid touching updated_at column so we don't pollute
        // change history every time someone logs in.
        $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
    }
}
