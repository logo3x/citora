<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AppointmentShareToken extends Model
{
    protected $fillable = ['appointment_id', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public static function generateFor(Appointment $appointment, int $validDays = 60): self
    {
        return static::firstOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'token' => static::uniqueToken(),
                'expires_at' => now()->addDays($validDays),
            ],
        );
    }

    private static function uniqueToken(): string
    {
        do {
            $token = Str::lower(Str::random(8));
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
