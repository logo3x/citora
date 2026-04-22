<?php

namespace App\Services;

use App\Contracts\MessagingChannel;
use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use App\Models\AppointmentShareToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppointmentNotificationService
{
    public function __construct(private MessagingChannel $channel) {}

    public function notifyCreated(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $date = Carbon::parse($appointment->starts_at)->translatedFormat('D d M');
        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $employee = $appointment->employee?->name ?? 'Por asignar';
        $service = $appointment->service->name;
        $business = $appointment->business->name;
        $price = '$'.number_format($appointment->service->price);
        $customer = $appointment->customer->name;
        $link = $this->shareLink($appointment);

        $this->sendEmail($appointment->customer->email, $appointment, AppointmentStatusMail::EVENT_CREATED, AppointmentStatusMail::ROLE_CUSTOMER, ['share_link' => $link]);
        $this->sendEmail($appointment->employee?->email, $appointment, AppointmentStatusMail::EVENT_CREATED, AppointmentStatusMail::ROLE_EMPLOYEE, ['share_link' => $link]);
        $this->sendEmail($appointment->business->email, $appointment, AppointmentStatusMail::EVENT_CREATED, AppointmentStatusMail::ROLE_OWNER, ['share_link' => $link]);

        $this->sendTemplateTo(
            $appointment->customer->phone,
            'appointment.confirmed.customer',
            [
                1 => $customer,
                2 => $business,
                3 => $service,
                4 => $employee,
                5 => $date,
                6 => $time,
                7 => $price,
                8 => $link,
            ],
            "✅ Cita confirmada en {$business}. {$service} con {$employee}. {$date} {$time}. Valor: {$price}. Detalles: {$link}"
        );

        $internalVars = [
            1 => $business,
            2 => $customer,
            3 => $service,
            4 => $employee,
            5 => $date,
            6 => $time,
            7 => $price,
            8 => $link,
        ];

        $this->sendTemplateTo(
            $appointment->employee?->phone,
            'appointment.new.internal',
            $internalVars,
            "📋 Nueva cita en {$business}. Cliente: {$customer}. {$service}. {$date} {$time}. Gestionar: {$link}"
        );

        $this->sendTemplateTo(
            $appointment->business->phone,
            'appointment.new.internal',
            $internalVars,
            "🔔 Nueva cita en {$business}. {$customer} - {$service} con {$employee}. {$date} {$time}. {$price}. Gestionar: {$link}"
        );
    }

    public function notifyCancelled(Appointment $appointment, string $changedBy = 'sistema'): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $date = Carbon::parse($appointment->starts_at)->translatedFormat('D d M');
        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;
        $service = $appointment->service->name;
        $customer = $appointment->customer->name;
        $byLabel = match ($changedBy) {
            'cliente' => 'Cancelada por el cliente',
            'negocio' => 'Cancelada por el negocio',
            default => '',
        };

        $emailExtra = ['changed_by' => $byLabel];
        $this->sendEmail($appointment->customer->email, $appointment, AppointmentStatusMail::EVENT_CANCELLED, AppointmentStatusMail::ROLE_CUSTOMER, $emailExtra);
        $this->sendEmail($appointment->employee?->email, $appointment, AppointmentStatusMail::EVENT_CANCELLED, AppointmentStatusMail::ROLE_EMPLOYEE, $emailExtra);
        $this->sendEmail($appointment->business->email, $appointment, AppointmentStatusMail::EVENT_CANCELLED, AppointmentStatusMail::ROLE_OWNER, $emailExtra);

        $vars = [
            1 => $business,
            2 => $customer,
            3 => $service,
            4 => $date,
            5 => $time,
            6 => $byLabel,
        ];

        $this->sendTemplateTo(
            $appointment->customer->phone,
            'appointment.cancelled',
            $vars,
            "❌ Cita cancelada en {$business}. {$service}. {$date} {$time}. {$byLabel}"
        );

        $this->sendTemplateTo(
            $appointment->employee?->phone,
            'appointment.cancelled',
            $vars,
            "❌ Cita cancelada - {$business}. {$customer} - {$service}. {$date} {$time}. {$byLabel}"
        );

        $this->sendTemplateTo(
            $appointment->business->phone,
            'appointment.cancelled',
            $vars,
            "❌ Cita cancelada - {$business}. {$customer} - {$service}. {$date} {$time}. {$byLabel}"
        );
    }

    public function notifyCompleted(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'customer', 'business']);

        $url = rtrim(config('app.url'), '/')."/{$appointment->business->slug}";

        // No pre-approved template for this event yet — plain text only.
        $this->sendTo($appointment->customer->phone,
            "🎉 Gracias por tu visita a {$appointment->business->name}. Reserva de nuevo: {$url}"
        );

        $this->sendEmail($appointment->customer->email, $appointment, AppointmentStatusMail::EVENT_COMPLETED, AppointmentStatusMail::ROLE_CUSTOMER);
    }

    public function notifyRescheduled(Appointment $appointment, string $oldDate, string $oldTime, string $changedBy = 'sistema'): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $newDate = Carbon::parse($appointment->starts_at)->translatedFormat('D d M');
        $newTime = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;
        $service = $appointment->service->name;
        $byLabel = match ($changedBy) {
            'cliente' => 'Reprogramada por el cliente',
            'negocio' => 'Reprogramada por el negocio',
            default => '',
        };

        $vars = [
            1 => $business,
            2 => $service,
            3 => $oldDate,
            4 => $oldTime,
            5 => $newDate,
            6 => $newTime,
            7 => $byLabel,
        ];

        $fallback = "🔄 Cita reprogramada - {$business}. {$service}. Antes: {$oldDate} {$oldTime}. Ahora: {$newDate} {$newTime}. {$byLabel}";

        $this->sendTemplateTo($appointment->customer->phone, 'appointment.rescheduled', $vars, $fallback);
        $this->sendTemplateTo($appointment->employee?->phone, 'appointment.rescheduled', $vars, $fallback);
        $this->sendTemplateTo($appointment->business->phone, 'appointment.rescheduled', $vars, $fallback);

        $emailExtra = [
            'old_date' => $oldDate,
            'old_time' => $oldTime,
            'changed_by' => $byLabel,
        ];
        $this->sendEmail($appointment->customer->email, $appointment, AppointmentStatusMail::EVENT_RESCHEDULED, AppointmentStatusMail::ROLE_CUSTOMER, $emailExtra);
        $this->sendEmail($appointment->employee?->email, $appointment, AppointmentStatusMail::EVENT_RESCHEDULED, AppointmentStatusMail::ROLE_EMPLOYEE, $emailExtra);
        $this->sendEmail($appointment->business->email, $appointment, AppointmentStatusMail::EVENT_RESCHEDULED, AppointmentStatusMail::ROLE_OWNER, $emailExtra);
    }

    public function notifyReminder24h(Appointment $appointment): void
    {
        $this->sendReminder($appointment, 'mañana');
    }

    public function notifyReminder1h(Appointment $appointment): void
    {
        $this->sendReminder($appointment, 'en 1 hora');
    }

    private function sendReminder(Appointment $appointment, string $whenLabel): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;
        $employee = $appointment->employee?->name ?? 'Cualquier profesional';
        $service = $appointment->service->name;
        $customer = $appointment->customer->name;
        $link = $this->shareLink($appointment);

        $emailEvent = $whenLabel === 'mañana' ? AppointmentStatusMail::EVENT_REMINDER_24H : AppointmentStatusMail::EVENT_REMINDER_1H;
        $emailExtra = ['share_link' => $link];
        $this->sendEmail($appointment->customer->email, $appointment, $emailEvent, AppointmentStatusMail::ROLE_CUSTOMER, $emailExtra);
        $this->sendEmail($appointment->employee?->email, $appointment, $emailEvent, AppointmentStatusMail::ROLE_EMPLOYEE, $emailExtra);
        if ($whenLabel === 'mañana') {
            $this->sendEmail($appointment->business->email, $appointment, $emailEvent, AppointmentStatusMail::ROLE_OWNER, $emailExtra);
        }

        $this->sendTemplateTo(
            $appointment->customer->phone,
            'appointment.reminder.customer',
            [
                1 => $customer,
                2 => $whenLabel,
                3 => $business,
                4 => $service,
                5 => $employee,
                6 => $time,
                7 => $link,
            ],
            "⏰ {$whenLabel} {$time} tienes cita en {$business}: {$service} con {$employee}. Detalles: {$link}"
        );

        $internalVars = [
            1 => $business,
            2 => $whenLabel,
            3 => $customer,
            4 => $service,
            5 => $time,
        ];

        $this->sendTemplateTo(
            $appointment->employee?->phone,
            'appointment.reminder.internal',
            $internalVars,
            "⏰ {$whenLabel} {$time} cita con {$customer} en {$business}: {$service}."
        );

        // Owner only on 24h reminder — avoid over-notifying.
        if ($whenLabel === 'mañana') {
            $this->sendTemplateTo(
                $appointment->business->phone,
                'appointment.reminder.internal',
                $internalVars,
                "⏰ {$whenLabel} {$time} cita: {$customer}, {$service}, {$employee}."
            );
        }
    }

    public function notifyBusinessCreated(string $ownerPhone, string $businessName, string $slug): void
    {
        $url = rtrim(config('app.url'), '/')."/{$slug}";

        // Plain-text only (no template yet).
        $this->sendTo($ownerPhone,
            "🎉 Tu negocio {$businessName} está listo. Comparte: {$url}"
        );
    }

    public function notifyEmployeeRegistered(string $employeePhone, string $employeeName, string $businessName): void
    {
        $this->sendTemplateTo(
            $employeePhone,
            'employee.welcome',
            [
                1 => $businessName,
                2 => $employeeName,
            ],
            "👋 Bienvenido a {$businessName}, {$employeeName}. Recibirás tus citas por este medio."
        );
    }

    /**
     * @param  array<int|string, string>  $variables
     */
    private function sendTemplateTo(?string $phone, string $templateKey, array $variables, string $fallbackText): void
    {
        if (! $phone) {
            return;
        }

        $this->channel->sendTemplate($phone, $templateKey, $variables, $fallbackText);
    }

    private function sendTo(?string $phone, string $message): void
    {
        if (! $phone) {
            return;
        }

        $this->channel->send($phone, $message);
    }

    private function shareLink(Appointment $appointment): string
    {
        $token = AppointmentShareToken::generateFor($appointment);

        return rtrim(config('app.url'), '/').'/c/'.$token->token;
    }

    /**
     * Send an appointment-status email in parallel to the WhatsApp/SMS channel.
     * Silently skipped when the recipient has no email on file.
     *
     * @param  array<string, mixed>  $extra
     */
    private function sendEmail(?string $email, Appointment $appointment, string $event, string $role, array $extra = []): void
    {
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new AppointmentStatusMail($appointment, $event, $role, $extra));
        } catch (\Throwable $e) {
            Log::warning('AppointmentStatusMail failed: '.$e->getMessage(), [
                'appointment_id' => $appointment->id,
                'event' => $event,
                'role' => $role,
                'email' => $email,
            ]);
        }
    }
}
