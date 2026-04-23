<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\AppointmentShareToken;
use App\Services\TimeSlotService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppointmentShareController extends Controller
{
    public function show(string $token): View
    {
        $appointment = $this->resolveAppointment($token);

        return view('appointment-share', [
            'appointment' => $appointment,
            'shareToken' => $token,
        ]);
    }

    public function confirm(string $token): RedirectResponse
    {
        $appointment = $this->resolveAppointment($token);

        if (! $this->isManageable($appointment)) {
            return redirect()
                ->route('appointment.share', ['token' => $token])
                ->with('error', 'Esta cita ya no se puede modificar.');
        }

        if ($appointment->status === AppointmentStatus::Confirmed) {
            return redirect()
                ->route('appointment.share', ['token' => $token])
                ->with('info', 'La cita ya estaba confirmada.');
        }

        $appointment->update(['status' => AppointmentStatus::Confirmed]);

        return redirect()
            ->route('appointment.share', ['token' => $token])
            ->with('success', 'Cita confirmada. Se avisó al cliente.');
    }

    public function cancel(string $token): RedirectResponse
    {
        $appointment = $this->resolveAppointment($token);

        if (! $this->isManageable($appointment)) {
            return redirect()
                ->route('appointment.share', ['token' => $token])
                ->with('error', 'Esta cita ya no se puede modificar.');
        }

        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        return redirect()
            ->route('appointment.share', ['token' => $token])
            ->with('success', 'Cita cancelada. Se avisó al cliente.');
    }

    public function rescheduleForm(string $token): View
    {
        $appointment = $this->resolveAppointment($token);

        if (! $this->isManageable($appointment)) {
            throw new NotFoundHttpException;
        }

        $appointment->load(['service', 'employee', 'business']);

        return view('customer.reschedule', [
            'appointment' => $appointment,
            'slotsUrl' => route('appointment.share.reschedule.slots', ['token' => $token]),
            'saveUrl' => route('appointment.share.reschedule.save', ['token' => $token]),
            'backUrl' => route('appointment.share', ['token' => $token]),
            'backLabel' => '← Volver a la cita',
            'successRedirect' => route('appointment.share', ['token' => $token]),
        ]);
    }

    public function rescheduleSlots(string $token, Request $request, TimeSlotService $timeSlotService): JsonResponse
    {
        $appointment = $this->resolveAppointment($token);

        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $appointment->load(['service', 'employee', 'business']);

        $slots = $timeSlotService->getAvailableSlots(
            $appointment->business,
            $request->date,
            $appointment->service,
            $appointment->employee,
        );

        return response()->json($slots);
    }

    public function reschedule(string $token, Request $request): JsonResponse
    {
        $appointment = $this->resolveAppointment($token);

        if (! $this->isManageable($appointment)) {
            return response()->json(['error' => 'Esta cita ya no se puede modificar'], 422);
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $appointment->load('service');

        $startsAt = Carbon::parse("{$validated['date']} {$validated['time']}");
        $endsAt = $startsAt->copy()->addMinutes($appointment->service->duration_minutes);

        if ($appointment->employee_id) {
            $overlap = Appointment::where('employee_id', $appointment->employee_id)
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', AppointmentStatus::Cancelled)
                ->where('starts_at', '<', $endsAt)
                ->where('ends_at', '>', $startsAt)
                ->exists();

            if ($overlap) {
                return response()->json(['error' => 'Ese horario ya no está disponible'], 422);
            }
        }

        $appointment->update([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cita reprogramada exitosamente',
            'date' => $startsAt->translatedFormat('l d \\d\\e F'),
            'time' => $startsAt->format('g:i A'),
        ]);
    }

    private function resolveAppointment(string $token): Appointment
    {
        $share = AppointmentShareToken::with([
            'appointment.service.media',
            'appointment.employee',
            'appointment.customer',
            'appointment.business.media',
        ])
            ->where('token', $token)
            ->first();

        if (! $share || $share->isExpired()) {
            throw new NotFoundHttpException;
        }

        return $share->appointment;
    }

    private function isManageable(Appointment $appointment): bool
    {
        return in_array($appointment->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
            && $appointment->starts_at > now();
    }
}
