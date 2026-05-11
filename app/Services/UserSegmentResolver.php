<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserSegmentResolver
{
    public const SEGMENT_ALL = 'all';

    public const SEGMENT_BUSINESS_OWNERS = 'business_owners';

    public const SEGMENT_INACTIVE_7D = 'inactive_7d';

    public const SEGMENT_INACTIVE_30D = 'inactive_30d';

    public const SEGMENT_NEW_LAST_7D = 'new_last_7d';

    public const SEGMENT_NO_APPOINTMENTS = 'no_appointments';

    public const SEGMENT_NEAR_LIMIT = 'near_limit';

    public const SEGMENT_PAID_PLAN = 'paid_plan';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::SEGMENT_ALL => '📬 Todos los business_owner',
            self::SEGMENT_BUSINESS_OWNERS => '🏢 Solo dueños activos',
            self::SEGMENT_INACTIVE_7D => '🔥 Inactivos > 7 días (sin crear cita)',
            self::SEGMENT_INACTIVE_30D => '💤 Inactivos > 30 días (último login antiguo)',
            self::SEGMENT_NEW_LAST_7D => '🆕 Nuevos (registrados últimos 7 días)',
            self::SEGMENT_NO_APPOINTMENTS => '🤷 Sin citas creadas nunca',
            self::SEGMENT_NEAR_LIMIT => '🎯 Cerca del límite del plan (>80%)',
            self::SEGMENT_PAID_PLAN => '💰 Con plan pago activo',
        ];
    }

    /**
     * Returns a User query filtered by the given segment.
     *
     * @return Builder<User>
     */
    public function query(string $segment): Builder
    {
        $query = User::query()
            ->role('business_owner')
            ->whereNotNull('email');

        return match ($segment) {
            self::SEGMENT_ALL,
            self::SEGMENT_BUSINESS_OWNERS => $query,

            self::SEGMENT_NEW_LAST_7D => $query->where('created_at', '>=', now()->subDays(7)),

            self::SEGMENT_INACTIVE_7D => $query->whereNotIn('id', function ($sub) {
                $sub->from('appointments')
                    ->join('businesses', 'businesses.id', '=', 'appointments.business_id')
                    ->join('users as u', 'u.business_id', '=', 'businesses.id')
                    ->where('appointments.created_at', '>=', now()->subDays(7))
                    ->select('u.id');
            }),

            self::SEGMENT_INACTIVE_30D => $query->where(function ($q) {
                $q->whereNull('last_login_at')
                    ->orWhere('last_login_at', '<', now()->subDays(30));
            }),

            self::SEGMENT_NO_APPOINTMENTS => $query->whereDoesntHave('business.appointments'),

            self::SEGMENT_NEAR_LIMIT => $query->whereHas('business', function ($b) {
                $b->whereRaw('(
                    SELECT COUNT(*) FROM appointments
                    WHERE appointments.business_id = businesses.id
                    AND DATE_FORMAT(appointments.starts_at, "%Y-%m") = ?
                ) >= (businesses.monthly_appointment_limit * 0.8)', [now()->format('Y-m')]);
            }),

            self::SEGMENT_PAID_PLAN => $query->whereHas('business.payments', function ($p) {
                $p->where('status', 'approved')
                    ->where('paid_at', '>=', now()->subDays(35));
            }),

            default => $query,
        };
    }

    public function count(string $segment): int
    {
        return $this->query($segment)->count();
    }
}
