<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request, WhatsAppService $whatsApp): Response
    {
        $body = trim($request->input('Body', ''));
        $from = $request->input('From', '');
        $buttonPayload = $request->input('ButtonPayload', $request->input('ButtonText', ''));

        Log::info('WhatsApp webhook', ['from' => $from, 'body' => $body, 'button' => $buttonPayload]);

        $action = strtoupper($buttonPayload ?: $body);

        // Button responses from templates: "Confirm" or "Cancel"
        if (in_array($action, ['CONFIRM', 'CANCEL'])) {
            return $this->handleButtonResponse($from, $action, $whatsApp);
        }

        // Text responses: CONFIRMAR CITA-X, CANCELAR CITA-X, COMPLETAR CITA-X
        if (preg_match('/^(CONFIRMAR|CANCELAR|COMPLETAR)\s+CITA-(\d+)$/i', $action, $matches)) {
            return $this->handleTextResponse($from, strtoupper($matches[1]), (int) $matches[2], $whatsApp);
        }

        return response('OK', 200);
    }

    private function handleButtonResponse(string $from, string $action, WhatsAppService $whatsApp): Response
    {
        $phone = preg_replace('/[^0-9]/', '', $from);
        $cached = cache()->pull("wa_pending:{$phone}");

        if (! $cached) {
            $whatsApp->send($from, '⚠️ No hay citas pendientes de respuesta.');

            return response('OK', 200);
        }

        $appointment = Appointment::with(['service', 'employee', 'customer', 'business'])
            ->find($cached['appointment_id']);

        if (! $appointment) {
            $whatsApp->send($from, '⚠️ Cita no encontrada.');

            return response('OK', 200);
        }

        $cachedAction = $cached['action'] ?? 'confirm';

        if ($cachedAction === 'complete') {
            return $this->handleCompletion($appointment, $action, $whatsApp, $from);
        }

        if ($cachedAction === 'reschedule_confirm') {
            return $this->handleRescheduleConfirmation($appointment, $action, $whatsApp, $from);
        }

        // Default: confirm/cancel appointment
        if ($action === 'CONFIRM') {
            if ($appointment->status === AppointmentStatus::Pending) {
                $appointment->update(['status' => AppointmentStatus::Confirmed]);
                $whatsApp->send($from, '✅ ¡Cita confirmada exitosamente!');
            } else {
                $whatsApp->send($from, "⚠️ Esta cita ya tiene estado: {$appointment->status->value}");
            }
        } else {
            if ($appointment->status !== AppointmentStatus::Cancelled) {
                $appointment->update(['status' => AppointmentStatus::Cancelled]);
                $whatsApp->send($from, '❌ Cita cancelada.');
            } else {
                $whatsApp->send($from, '⚠️ Esta cita ya estaba cancelada.');
            }
        }

        return response('OK', 200);
    }

    private function handleCompletion(Appointment $appointment, string $action, WhatsAppService $whatsApp, string $from): Response
    {
        if ($action === 'CONFIRM') {
            $appointment->update(['status' => AppointmentStatus::Completed]);
            $whatsApp->send($from, '✅ ¡Servicio completado! Se notificará al cliente.');
        } else {
            $whatsApp->send($from, '⚠️ El servicio no fue marcado como completado.');
        }

        return response('OK', 200);
    }

    private function handleRescheduleConfirmation(Appointment $appointment, string $action, WhatsAppService $whatsApp, string $from): Response
    {
        if ($action === 'CONFIRM') {
            if ($appointment->status === AppointmentStatus::Pending) {
                $appointment->update(['status' => AppointmentStatus::Confirmed]);
            }

            $whatsApp->send($from, '✅ ¡Cita reprogramada confirmada!');
        } else {
            $appointment->update(['status' => AppointmentStatus::Cancelled]);
            $whatsApp->send($from, '❌ Cita reprogramada cancelada.');
        }

        return response('OK', 200);
    }

    private function handleTextResponse(string $from, string $action, int $appointmentId, WhatsAppService $whatsApp): Response
    {
        $appointment = Appointment::with(['service', 'employee', 'customer', 'business'])
            ->find($appointmentId);

        if (! $appointment) {
            $whatsApp->send($from, '⚠️ No se encontró la cita.');

            return response('OK', 200);
        }

        match ($action) {
            'CONFIRMAR' => $this->textConfirm($appointment, $whatsApp, $from),
            'CANCELAR' => $this->textCancel($appointment, $whatsApp, $from),
            'COMPLETAR' => $this->textComplete($appointment, $whatsApp, $from),
            default => null,
        };

        return response('OK', 200);
    }

    private function textConfirm(Appointment $appointment, WhatsAppService $whatsApp, string $from): void
    {
        if ($appointment->status !== AppointmentStatus::Pending) {
            $whatsApp->send($from, "⚠️ Esta cita ya tiene estado: {$appointment->status->value}");

            return;
        }

        $appointment->update(['status' => AppointmentStatus::Confirmed]);
        $whatsApp->send($from, '✅ ¡Cita confirmada!');
    }

    private function textCancel(Appointment $appointment, WhatsAppService $whatsApp, string $from): void
    {
        if ($appointment->status === AppointmentStatus::Cancelled) {
            $whatsApp->send($from, '⚠️ Esta cita ya estaba cancelada.');

            return;
        }

        $appointment->update(['status' => AppointmentStatus::Cancelled]);
        $whatsApp->send($from, '❌ Cita cancelada.');
    }

    private function textComplete(Appointment $appointment, WhatsAppService $whatsApp, string $from): void
    {
        if ($appointment->status === AppointmentStatus::Completed) {
            $whatsApp->send($from, '⚠️ Esta cita ya fue completada.');

            return;
        }

        $appointment->update(['status' => AppointmentStatus::Completed]);
        $whatsApp->send($from, '✅ ¡Servicio completado!');
    }
}
