<?php

namespace App\Console\Commands;

use App\Enums\AppointmentStatus;
use App\Jobs\SendWhatsAppNotification;
use App\Models\Appointment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('appointments:send-reminders')]
#[Description('Envía recordatorios por WhatsApp (24h y 1h antes)')]
class SendAppointmentReminders extends Command
{
    public function handle(): int
    {
        $count24h = $this->sendReminders('appointment.reminder_24h', 23, 25, 'reminder_24h_sent_at');
        $count1h = $this->sendReminders('appointment.reminder_1h', 0.5, 1.5, 'reminder_1h_sent_at');

        $this->info("Recordatorios 24h: {$count24h} | 1h: {$count1h}");

        return self::SUCCESS;
    }

    private function sendReminders(string $event, float $hoursFrom, float $hoursTo, string $sentAtColumn): int
    {
        $appointments = Appointment::with(['service', 'employee', 'customer', 'business'])
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
            ->whereBetween('starts_at', [
                now()->addMinutes((int) ($hoursFrom * 60)),
                now()->addMinutes((int) ($hoursTo * 60)),
            ])
            ->whereNull($sentAtColumn)
            ->get();

        foreach ($appointments as $appointment) {
            SendWhatsAppNotification::dispatch($event, $appointment);
            $appointment->update([$sentAtColumn => now()]);
        }

        return $appointments->count();
    }
}
