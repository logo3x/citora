<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';
    case LateArrival = 'late_arrival';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Confirmed => 'Confirmada',
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
            self::NoShow => 'No llegó',
            self::LateArrival => 'Llegó tarde',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::NoShow => 'gray',
            self::LateArrival => 'warning',
        };
    }

    public function hexColor(): string
    {
        return match ($this) {
            self::Pending => '#F59E0B',
            self::Confirmed => '#2563EB',
            self::Completed => '#059669',
            self::Cancelled => '#9CA3AF',
            self::NoShow => '#6B7280',
            self::LateArrival => '#F97316',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Completed, self::Cancelled, self::NoShow]);
    }
}
