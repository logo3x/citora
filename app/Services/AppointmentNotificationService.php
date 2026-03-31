<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentNotificationService
{
    public function __construct(private WhatsAppService $whatsApp) {}

    public function notifyCreated(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $date = Carbon::parse($appointment->starts_at)->translatedFormat('l d \\d\\e F');
        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $employee = $appointment->employee?->name ?? 'Por asignar';
        $service = $appointment->service->name;
        $business = $appointment->business->name;
        $price = '$'.number_format($appointment->service->price);

        // Al cliente
        $ownerPhone = $appointment->business->phone;
        $contactLine = $ownerPhone ? "\n📞 ¿Necesitas reprogramar? Contáctanos: wa.me/57{$ownerPhone}" : '';

        $this->sendTo($appointment->customer->phone,
            "✅ *¡Cita confirmada en {$business}!*\n\n"
            ."📋 {$service}\n👤 {$employee}\n📅 {$date}\n🕐 {$time}\n💰 {$price}\n\n"
            ."¡Te esperamos!{$contactLine}"
        );

        // Al empleado
        $this->sendTo($appointment->employee?->phone,
            "📋 *Nueva cita asignada - {$business}*\n\n"
            ."🧑 Cliente: {$appointment->customer->name}\n"
            ."✂️ Servicio: {$service}\n📅 {$date}\n🕐 {$time}\n"
            .($appointment->notes ? "📝 Notas: {$appointment->notes}\n" : '')
        );

        // Al propietario
        $this->sendTo($appointment->business->phone,
            "🔔 *Nueva cita en {$business}*\n\n"
            ."🧑 Cliente: {$appointment->customer->name}\n"
            ."✂️ {$service}\n👤 {$employee}\n📅 {$date}\n🕐 {$time}\n💰 {$price}"
        );
    }

    public function notifyCancelled(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $date = Carbon::parse($appointment->starts_at)->translatedFormat('l d \\d\\e F');
        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;

        $this->sendTo($appointment->customer->phone,
            "❌ *Cita cancelada - {$business}*\n\n"
            ."📋 {$appointment->service->name}\n📅 {$date}\n🕐 {$time}\n\n"
            .'Puedes agendar una nueva cita en cualquier momento.'
        );

        $this->sendTo($appointment->employee?->phone,
            "❌ *Cita cancelada - {$business}*\n\n"
            ."🧑 {$appointment->customer->name}\n📋 {$appointment->service->name}\n📅 {$date} {$time}"
        );

        $this->sendTo($appointment->business->phone,
            "❌ *Cita cancelada - {$business}*\n\n"
            ."🧑 {$appointment->customer->name}\n📋 {$appointment->service->name}\n📅 {$date} {$time}"
        );
    }

    public function notifyCompleted(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'customer', 'business']);

        $this->sendTo($appointment->customer->phone,
            "🎉 *¡Gracias por tu visita a {$appointment->business->name}!*\n\n"
            ."Esperamos que hayas disfrutado tu {$appointment->service->name}.\n\n"
            ."¡Te esperamos pronto! Reserva tu próxima cita aquí:\n"
            .'🔗 '.rtrim(config('app.url'), '/')."/{$appointment->business->slug}"
        );
    }

    public function notifyRescheduled(Appointment $appointment, string $oldDate, string $oldTime): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $newDate = Carbon::parse($appointment->starts_at)->translatedFormat('l d \\d\\e F');
        $newTime = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;

        $msg = "🔄 *Cita reprogramada - {$business}*\n\n"
            ."📋 {$appointment->service->name}\n"
            ."❌ Antes: {$oldDate} {$oldTime}\n"
            ."✅ Ahora: {$newDate} {$newTime}";

        $this->sendTo($appointment->customer->phone, $msg);
        $this->sendTo($appointment->employee?->phone, $msg);
        $this->sendTo($appointment->business->phone, $msg);
    }

    public function notifyReminder24h(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;
        $employee = $appointment->employee?->name ?? 'Cualquier profesional';
        $service = $appointment->service->name;
        $customer = $appointment->customer->name;

        $this->sendTo($appointment->customer->phone,
            "⏰ *Recordatorio - {$business}*\n\nTu cita es *mañana* a las *{$time}*\n📋 {$service}\n👤 {$employee}\n\n¡No olvides asistir!"
        );

        $this->sendTo($appointment->employee?->phone,
            "⏰ *Recordatorio - {$business}*\n\nTienes cita *mañana* a las *{$time}*\n🧑 {$customer}\n📋 {$service}"
        );

        $this->sendTo($appointment->business->phone,
            "⏰ *Recordatorio - {$business}*\n\nCita mañana a las *{$time}*\n🧑 {$customer}\n👤 {$employee}\n📋 {$service}"
        );
    }

    public function notifyReminder1h(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;

        $this->sendTo($appointment->customer->phone,
            "🔔 *Tu cita es en 1 hora - {$business}*\n\n📋 {$appointment->service->name}\n🕐 {$time}\n👤 ".($appointment->employee?->name ?? 'Cualquier profesional')."\n\n¡Te esperamos!"
        );

        $this->sendTo($appointment->employee?->phone,
            "🔔 *Cita en 1 hora - {$business}*\n\n🧑 {$appointment->customer->name}\n📋 {$appointment->service->name}\n🕐 {$time}"
        );
    }

    public function notifyBusinessCreated(string $ownerPhone, string $businessName, string $slug): void
    {
        $this->sendTo($ownerPhone,
            "🎉 *¡Felicidades! Tu negocio está listo*\n\n🏪 {$businessName}\n🔗 Tu enlace público: ".rtrim(config('app.url'), '/')."/{$slug}\n\nComparte este enlace con tus clientes para que reserven citas."
        );
    }

    public function notifyEmployeeRegistered(string $employeePhone, string $employeeName, string $businessName): void
    {
        $this->sendTo($employeePhone,
            "👋 *¡Bienvenido al equipo de {$businessName}!*\n\nHola {$employeeName}, has sido registrado como profesional.\nRecibirás notificaciones de tus citas por este medio."
        );
    }

    private function sendTo(?string $phone, string $message): void
    {
        if (! $phone) {
            return;
        }

        $this->whatsApp->send($phone, $message);
    }
}
