<?php

namespace App\Mail;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public const EVENT_CREATED = 'created';

    public const EVENT_CANCELLED = 'cancelled';

    public const EVENT_RESCHEDULED = 'rescheduled';

    public const EVENT_COMPLETED = 'completed';

    public const EVENT_REMINDER_24H = 'reminder_24h';

    public const EVENT_REMINDER_1H = 'reminder_1h';

    public const ROLE_CUSTOMER = 'customer';

    public const ROLE_EMPLOYEE = 'employee';

    public const ROLE_OWNER = 'owner';

    /**
     * @param  array<string, mixed>  $extra  Extra data: old_date, old_time, changed_by, share_link, when_label
     */
    public function __construct(
        public Appointment $appointment,
        public string $event,
        public string $role,
        public array $extra = [],
    ) {
        $this->appointment->loadMissing(['service', 'employee', 'customer', 'business']);
    }

    public function envelope(): Envelope
    {
        $business = $this->appointment->business->name;

        $subject = match ($this->event) {
            self::EVENT_CREATED => $this->role === self::ROLE_CUSTOMER
                ? "✅ Cita confirmada en {$business}"
                : "🔔 Nueva cita en {$business}",
            self::EVENT_CANCELLED => "❌ Cita cancelada en {$business}",
            self::EVENT_RESCHEDULED => "🔄 Cita reprogramada en {$business}",
            self::EVENT_COMPLETED => "🎉 Gracias por tu visita a {$business}",
            self::EVENT_REMINDER_24H => "⏰ Recordatorio: tu cita en {$business} es mañana",
            self::EVENT_REMINDER_1H => "🔔 Tu cita en {$business} es en 1 hora",
            default => "Citora — {$business}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.appointment-status',
            with: [
                'appointment' => $this->appointment,
                'event' => $this->event,
                'role' => $this->role,
                'extra' => $this->extra,
                'date' => Carbon::parse($this->appointment->starts_at)->translatedFormat('l d \\d\\e F \\d\\e Y'),
                'time' => Carbon::parse($this->appointment->starts_at)->format('g:i A'),
            ],
        );
    }
}
