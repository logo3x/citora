<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\TimeSlotService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerAppointmentController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $upcoming = Appointment::with(['service', 'employee', 'business', 'business.media'])
            ->where('customer_id', $user->id)
            ->where('starts_at', '>=', now())
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
            ->orderBy('starts_at')
            ->get();

        $past = Appointment::with(['service', 'employee', 'business', 'business.media'])
            ->where('customer_id', $user->id)
            ->where(fn ($q) => $q->where('starts_at', '<', now())->orWhereIn('status', [AppointmentStatus::Completed, AppointmentStatus::Cancelled]))
            ->orderByDesc('starts_at')
            ->limit(20)
            ->get();

        return view('customer.appointments', compact('upcoming', 'past', 'user'));
    }

    public function cancel(Appointment $appointment): JsonResponse
    {
        $user = auth()->user();

        if ((int) $appointment->customer_id !== (int) $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($appointment->status === AppointmentStatus::Cancelled) {
            return response()->json(['error' => 'Esta cita ya está cancelada'], 422);
        }

        if ($appointment->starts_at <= now()) {
            return response()->json(['error' => 'No puedes cancelar una cita que ya pasó'], 422);
        }

        $appointment->loadMissing('business');

        if (! $appointment->business->canCustomerCancel($appointment)) {
            $hours = $appointment->business->cancellation_min_hours;

            return response()->json([
                'error' => "Este negocio no permite cancelaciones con menos de {$hours} horas de anticipación. Por favor contacta directamente al negocio.",
            ], 422);
        }

        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        return response()->json(['success' => true, 'message' => 'Cita cancelada exitosamente']);
    }

    public function rescheduleForm(Appointment $appointment, TimeSlotService $timeSlotService): View
    {
        $user = auth()->user();

        abort_unless((int) $appointment->customer_id === (int) $user->id, 403);
        abort_unless(in_array($appointment->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed]), 403);
        abort_unless($appointment->starts_at > now(), 403);

        $appointment->load(['service', 'employee', 'business']);

        abort_unless($appointment->business->canCustomerReschedule($appointment), 403, "Este negocio no permite reprogramar con menos de {$appointment->business->reschedule_min_hours} horas de anticipación.");

        return view('customer.reschedule', compact('appointment'));
    }

    public function rescheduleSlots(Request $request, Appointment $appointment, TimeSlotService $timeSlotService): JsonResponse
    {
        $user = auth()->user();

        if ((int) $appointment->customer_id !== (int) $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

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

    public function reschedule(Request $request, Appointment $appointment): JsonResponse
    {
        $user = auth()->user();

        if ((int) $appointment->customer_id !== (int) $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (! in_array($appointment->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed])) {
            return response()->json(['error' => 'No se puede reprogramar esta cita'], 422);
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
}
