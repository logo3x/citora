<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\AppointmentNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public string $event,
        public ?Appointment $appointment = null,
        public array $extra = [],
    ) {}

    public function handle(AppointmentNotificationService $notifier): void
    {
        match ($this->event) {
            'appointment.created' => $notifier->notifyCreated($this->appointment),
            'appointment.cancelled' => $notifier->notifyCancelled($this->appointment),
            'appointment.completed' => $notifier->notifyCompleted($this->appointment),
            'appointment.rescheduled' => $notifier->notifyRescheduled(
                $this->appointment,
                $this->extra['old_date'] ?? '',
                $this->extra['old_time'] ?? '',
            ),
            'appointment.reminder_24h' => $notifier->notifyReminder24h($this->appointment),
            'appointment.reminder_1h' => $notifier->notifyReminder1h($this->appointment),
            'business.created' => $notifier->notifyBusinessCreated(
                $this->extra['phone'] ?? '',
                $this->extra['business_name'] ?? '',
                $this->extra['slug'] ?? '',
            ),
            'employee.registered' => $notifier->notifyEmployeeRegistered(
                $this->extra['phone'] ?? '',
                $this->extra['name'] ?? '',
                $this->extra['business_name'] ?? '',
            ),
            default => null,
        };
    }
}
