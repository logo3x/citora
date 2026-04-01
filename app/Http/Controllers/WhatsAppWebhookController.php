<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Twilio\Security\RequestValidator;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request, WhatsAppService $whatsApp): Response
    {
        if (! $this->validateTwilioSignature($request)) {
            Log::warning('WhatsApp webhook: invalid Twilio signature');

            return response('Unauthorized', 403);
        }

        $body = trim($request->input('Body', ''));
        $from = $request->input('From', '');
        $buttonPayload = $request->input('ButtonPayload', $request->input('ButtonText', ''));

        Log::info('WhatsApp webhook', ['from' => $from, 'body' => $body]);

        $action = strtoupper($buttonPayload ?: $body);

        if (in_array($action, ['CONFIRM', 'CANCEL'])) {
            return $this->handleButtonResponse($from, $action, $whatsApp);
        }

        if (preg_match('/^(CONFIRMAR|CANCELAR|COMPLETAR)\s+CITA-(\d+)$/i', $action, $matches)) {
            return $this->handleTextResponse($from, strtoupper($matches[1]), (int) $matches[2], $whatsApp);
        }

        return response('OK', 200);
    }

    private function validateTwilioSignature(Request $request): bool
    {
        $token = config('services.twilio.auth_token');

        if (! $token) {
            return true;
        }

        $validator = new RequestValidator($token);
        $signature = $request->header('X-Twilio-Signature', '');

        return $validator->validate($signature, $request->fullUrl(), $request->all());
    }

    private function handleButtonResponse(string $from, string $action, WhatsAppService $whatsApp): Response
    {
        $phone = preg_replace('/[^0-9]/', '', $from);
        $cached = cache()->pull("wa_pending:{$phone}");

        if (! $cached) {
            return response('OK', 200);
        }

        $appointment = Appointment::with(['service', 'employee', 'customer', 'business'])
            ->find($cached['appointment_id']);

        if (! $appointment) {
            return response('OK', 200);
        }

        if ($action === 'CONFIRM') {
            if ($appointment->status === AppointmentStatus::Pending) {
                $appointment->update(['status' => AppointmentStatus::Confirmed]);
                $whatsApp->send($from, '✅ ¡Cita confirmada!');
            }
        } else {
            if ($appointment->status !== AppointmentStatus::Cancelled) {
                $appointment->update(['status' => AppointmentStatus::Cancelled]);
                $whatsApp->send($from, '❌ Cita cancelada.');
            }
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

        $phone = preg_replace('/[^0-9]/', '', $from);
        $authorized = str_contains($phone, preg_replace('/[^0-9]/', '', $appointment->employee?->phone ?? ''))
            || str_contains($phone, preg_replace('/[^0-9]/', '', $appointment->business?->phone ?? ''))
            || str_contains($phone, preg_replace('/[^0-9]/', '', $appointment->customer?->phone ?? ''));

        if (! $authorized) {
            $whatsApp->send($from, '⚠️ No tienes permiso para modificar esta cita.');

            return response('OK', 200);
        }

        match ($action) {
            'CONFIRMAR' => $this->updateStatus($appointment, AppointmentStatus::Confirmed, AppointmentStatus::Pending, $whatsApp, $from),
            'CANCELAR' => $this->updateStatus($appointment, AppointmentStatus::Cancelled, null, $whatsApp, $from),
            'COMPLETAR' => $this->updateStatus($appointment, AppointmentStatus::Completed, null, $whatsApp, $from),
            default => null,
        };

        return response('OK', 200);
    }

    private function updateStatus(Appointment $appointment, AppointmentStatus $newStatus, ?AppointmentStatus $requiredCurrent, WhatsAppService $whatsApp, string $from): void
    {
        if ($requiredCurrent && $appointment->status !== $requiredCurrent) {
            $whatsApp->send($from, "⚠️ Esta cita ya tiene estado: {$appointment->status->value}");

            return;
        }

        if ($appointment->status === $newStatus) {
            $whatsApp->send($from, '⚠️ La cita ya tiene ese estado.');

            return;
        }

        $appointment->update(['status' => $newStatus]);

        $labels = [
            'confirmed' => '✅ ¡Cita confirmada!',
            'cancelled' => '❌ Cita cancelada.',
            'completed' => '✅ ¡Servicio completado!',
        ];

        $whatsApp->send($from, $labels[$newStatus->value] ?? '✅ Estado actualizado.');
    }
}
