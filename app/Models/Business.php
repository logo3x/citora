<?php

namespace App\Models;

use Database\Factories\BusinessFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['name', 'slogan', 'description', 'slug', 'email', 'phone', 'address', 'is_active'])]
class Business extends Model implements HasMedia
{
    /** @use HasFactory<BusinessFactory> */
    use HasFactory, InteractsWithMedia;

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('banner')->singleFile();
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany<Employee, $this>
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * @return HasMany<Service, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * @return HasMany<Appointment, $this>
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * @return HasMany<BusinessSchedule, $this>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(BusinessSchedule::class);
    }

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getMonthlyAppointmentCount(?string $period = null): int
    {
        $period ??= now()->format('Y-m');

        return $this->appointments()
            ->whereRaw("DATE_FORMAT(starts_at, '%Y-%m') = ?", [$period])
            ->count();
    }

    public function hasReachedMonthlyLimit(): bool
    {
        return $this->getMonthlyAppointmentCount() >= $this->monthly_appointment_limit;
    }

    public function isUnlockedForPeriod(?string $period = null): bool
    {
        $period ??= now()->format('Y-m');

        return $this->payments()
            ->where('period', $period)
            ->where('status', 'approved')
            ->exists();
    }

    public function canAcceptAppointments(): bool
    {
        if (! $this->hasReachedMonthlyLimit()) {
            return true;
        }

        return $this->isUnlockedForPeriod();
    }

    public function getRemainingAppointments(): int
    {
        $used = $this->getMonthlyAppointmentCount();
        $limit = $this->monthly_appointment_limit;

        return max(0, $limit - $used);
    }
}
