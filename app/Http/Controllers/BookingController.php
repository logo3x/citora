<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Jobs\SendWhatsAppNotification;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Service;
use App\Services\TimeSlotService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function show(Business $business): View
    {
        abort_unless($business->is_active, 404);

        $business->load([
            'services' => fn ($q) => $q->where('is_active', true),
            'services.media',
            'employees' => fn ($q) => $q->where('is_active', true),
            'employees.media',
            'employees.services',
            'schedules' => fn ($q) => $q->where('is_active', true),
        ]);

        $canBook = $business->canAcceptAppointments();

        return view('booking.show', compact('business', 'canBook'));
    }

    public function slots(Request $request, Business $business, TimeSlotService $timeSlotService): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'service_id' => ['required', Rule::exists('services', 'id')->where('business_id', $business->id)],
            'employee_id' => ['nullable', Rule::exists('employees', 'id')->where('business_id', $business->id)],
        ]);

        $service = Service::findOrFail($request->service_id);
        $employee = $request->employee_id ? Employee::find($request->employee_id) : null;

        $slots = $timeSlotService->getAvailableSlots(
            $business,
            $request->date,
            $service,
            $employee,
        );

        return response()->json($slots);
    }

    public function store(Request $request, Business $business): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Debes iniciar sesión'], 401);
        }

        if (! $business->canAcceptAppointments()) {
            return response()->json(['error' => 'Este negocio ha alcanzado su límite de citas del mes. Contacta al negocio.'], 422);
        }

        $validated = $request->validate([
            'service_id' => ['required', Rule::exists('services', 'id')->where('business_id', $business->id)],
            'employee_id' => ['nullable', Rule::exists('employees', 'id')->where('business_id', $business->id)],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($user->phone !== $validated['phone']) {
            $user->update(['phone' => $validated['phone']]);
        }

        $service = Service::findOrFail($validated['service_id']);
        $startsAt = Carbon::parse("{$validated['date']} {$validated['time']}");
        $endsAt = $startsAt->copy()->addMinutes($service->duration_minutes);

        $employeeId = $validated['employee_id'];

        if ($employeeId) {
            $overlap = Appointment::where('employee_id', $employeeId)
                ->where('status', '!=', AppointmentStatus::Cancelled)
                ->where('starts_at', '<', $endsAt)
                ->where('ends_at', '>', $startsAt)
                ->exists();

            if ($overlap) {
                return response()->json(['error' => 'Este horario ya no está disponible'], 422);
            }
        } else {
            $timeSlotService = app(TimeSlotService::class);
            $assignedEmployee = $timeSlotService->findAvailableEmployee(
                $business,
                $service,
                $validated['date'],
                $validated['time'],
            );

            if (! $assignedEmployee) {
                return response()->json(['error' => 'No hay profesionales disponibles en ese horario'], 422);
            }

            $employeeId = $assignedEmployee->id;
        }

        $appointment = $business->appointments()->create([
            'service_id' => $validated['service_id'],
            'employee_id' => $employeeId,
            'customer_id' => $user->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => AppointmentStatus::Confirmed,
            'notes' => $validated['notes'],
        ]);

        SendWhatsAppNotification::dispatch('appointment.created', $appointment);

        return response()->json([
            'success' => true,
            'message' => '¡Cita agendada exitosamente!',
            'appointment' => [
                'id' => $appointment->id,
                'service' => $service->name,
                'date' => $startsAt->translatedFormat('l d \\d\\e F, Y'),
                'time' => $startsAt->format('g:i A'),
            ],
        ]);
    }
}
