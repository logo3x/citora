<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\AppointmentNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    /** @var array<int> */
    public array $backoff = [10, 60, 300];

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

    public function failed(\Throwable $exception): void
    {
        Log::error('WhatsApp notification failed', [
            'event' => $this->event,
            'appointment_id' => $this->appointment?->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
