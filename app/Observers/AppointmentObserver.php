<?php

namespace App\Observers;

use App\Enums\AppointmentStatus;
use App\Jobs\SendWhatsAppNotification;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentObserver
{
    public function updated(Appointment $appointment): void
    {
        if ($appointment->wasChanged('status')) {
            match ($appointment->status) {
                AppointmentStatus::Cancelled => SendWhatsAppNotification::dispatch('appointment.cancelled', $appointment),
                AppointmentStatus::Completed => SendWhatsAppNotification::dispatch('appointment.completed', $appointment),
                default => null,
            };
        }

        if ($appointment->wasChanged('starts_at') && ! $appointment->wasChanged('status')) {
            $oldStartsAt = Carbon::parse($appointment->getOriginal('starts_at'));

            SendWhatsAppNotification::dispatch('appointment.rescheduled', $appointment, [
                'old_date' => $oldStartsAt->translatedFormat('l d \\d\\e F'),
                'old_time' => $oldStartsAt->format('g:i A'),
            ]);
        }
    }
}
