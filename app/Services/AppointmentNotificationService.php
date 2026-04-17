<?php

namespace App\Services;

use App\Contracts\MessagingChannel;
use App\Models\Appointment;
use Carbon\Carbon;

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

        $this->sendTo($appointment->customer->phone,
            "✅ Cita confirmada en {$business}. {$service} con {$employee}. {$date} {$time}. Valor: {$price}."
        );

        $this->sendTo($appointment->employee?->phone,
            "📋 Nueva cita en {$business}. Cliente: {$customer}. {$service}. {$date} {$time}."
        );

        $this->sendTo($appointment->business->phone,
            "🔔 Nueva cita en {$business}. {$customer} - {$service} con {$employee}. {$date} {$time}. {$price}."
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
            'cliente' => 'por el cliente',
            'negocio' => 'por el negocio',
            default => '',
        };

        $this->sendTo($appointment->customer->phone,
            "❌ Cita cancelada en {$business}. {$service}. {$date} {$time}. {$byLabel}"
        );

        $this->sendTo($appointment->employee?->phone,
            "❌ Cita cancelada - {$business}. {$customer} - {$service}. {$date} {$time}. {$byLabel}"
        );

        $this->sendTo($appointment->business->phone,
            "❌ Cita cancelada - {$business}. {$customer} - {$service}. {$date} {$time}. {$byLabel}"
        );
    }

    public function notifyCompleted(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'customer', 'business']);

        $url = rtrim(config('app.url'), '/')."/{$appointment->business->slug}";

        $this->sendTo($appointment->customer->phone,
            "🎉 Gracias por tu visita a {$appointment->business->name}. Reserva de nuevo: {$url}"
        );
    }

    public function notifyRescheduled(Appointment $appointment, string $oldDate, string $oldTime, string $changedBy = 'sistema'): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $newDate = Carbon::parse($appointment->starts_at)->translatedFormat('D d M');
        $newTime = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;
        $service = $appointment->service->name;
        $byLabel = match ($changedBy) {
            'cliente' => 'por el cliente',
            'negocio' => 'por el negocio',
            default => '',
        };

        $msg = "🔄 Cita reprogramada - {$business}. {$service}. Antes: {$oldDate} {$oldTime}. Ahora: {$newDate} {$newTime}. {$byLabel}";

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
            "⏰ Mañana {$time} tienes cita en {$business}: {$service} con {$employee}."
        );

        $this->sendTo($appointment->employee?->phone,
            "⏰ Mañana {$time} cita con {$customer} en {$business}: {$service}."
        );

        $this->sendTo($appointment->business->phone,
            "⏰ Mañana {$time} cita: {$customer}, {$service}, {$employee}."
        );
    }

    public function notifyReminder1h(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'employee', 'customer', 'business']);

        $time = Carbon::parse($appointment->starts_at)->format('g:i A');
        $business = $appointment->business->name;
        $employee = $appointment->employee?->name ?? 'Cualquier profesional';
        $service = $appointment->service->name;
        $customer = $appointment->customer->name;

        $this->sendTo($appointment->customer->phone,
            "🔔 Tu cita en {$business} es en 1h ({$time}). {$service} con {$employee}."
        );

        $this->sendTo($appointment->employee?->phone,
            "🔔 Cita en 1h - {$business}. {$customer} - {$service} a las {$time}."
        );
    }

    public function notifyBusinessCreated(string $ownerPhone, string $businessName, string $slug): void
    {
        $url = rtrim(config('app.url'), '/')."/{$slug}";

        $this->sendTo($ownerPhone,
            "🎉 Tu negocio {$businessName} está listo. Comparte: {$url}"
        );
    }

    public function notifyEmployeeRegistered(string $employeePhone, string $employeeName, string $businessName): void
    {
        $this->sendTo($employeePhone,
            "👋 Bienvenido a {$businessName}, {$employeeName}. Recibirás tus citas por este medio."
        );
    }

    private function sendTo(?string $phone, string $message): void
    {
        if (! $phone) {
            return;
        }

        $this->channel->send($phone, $message);
    }
}
