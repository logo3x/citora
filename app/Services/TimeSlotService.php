<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimeSlotService
{
    /**
     * @return array<string, string>
     */
    public function getAvailableSlots(
        Business $business,
        string $date,
        Service $service,
        ?Employee $employee = null,
    ): array {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $duration = $service->duration_minutes;

        $businessSchedule = $business->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $businessSchedule) {
            return [];
        }

        $bizOpen = Carbon::parse("{$date} {$businessSchedule->open_time}");
        $bizClose = Carbon::parse("{$date} {$businessSchedule->close_time}");

        if ($employee) {
            return $this->getSlotsForEmployee($employee, $date, $dayOfWeek, $duration, $bizOpen, $bizClose);
        }

        return $this->getSlotsForAnyEmployee($business, $service, $date, $dayOfWeek, $duration, $bizOpen, $bizClose);
    }

    /**
     * Busca un empleado disponible para un slot específico.
     * Usado al crear cita con "Cualquiera".
     */
    public function findAvailableEmployee(
        Business $business,
        Service $service,
        string $date,
        string $time,
    ): ?Employee {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $duration = $service->duration_minutes;
        $slotStart = Carbon::parse("{$date} {$time}");
        $slotEnd = $slotStart->copy()->addMinutes($duration);

        $employees = $business->employees()
            ->where('is_active', true)
            ->whereHas('services', fn ($q) => $q->where('services.id', $service->id))
            ->get();

        foreach ($employees as $employee) {
            if (! $this->isEmployeeAvailableAtSlot($employee, $dayOfWeek, $date, $slotStart, $slotEnd)) {
                continue;
            }

            return $employee;
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    private function getSlotsForEmployee(
        Employee $employee,
        string $date,
        int $dayOfWeek,
        int $duration,
        Carbon $bizOpen,
        Carbon $bizClose,
    ): array {
        $employeeSchedule = $employee->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if ($employeeSchedule) {
            if (! $employeeSchedule->is_active) {
                return [];
            }

            $empStart = Carbon::parse("{$date} {$employeeSchedule->start_time}");
            $empEnd = Carbon::parse("{$date} {$employeeSchedule->end_time}");

            $rangeStart = $empStart->max($bizOpen);
            $rangeEnd = $empEnd->min($bizClose);

            if ($rangeStart >= $rangeEnd) {
                return [];
            }
        } else {
            $rangeStart = $bizOpen;
            $rangeEnd = $bizClose;
        }

        $allSlots = $this->generateSlots($rangeStart, $rangeEnd, $duration);

        $existingAppointments = Appointment::where('employee_id', $employee->id)
            ->whereDate('starts_at', $date)
            ->where('status', '!=', AppointmentStatus::Cancelled)
            ->get(['starts_at', 'ends_at']);

        return $this->removeConflicts($allSlots, $existingAppointments, $date, $duration);
    }

    /**
     * Cuando se selecciona "Cualquiera": muestra un slot si AL MENOS un empleado está disponible.
     *
     * @return array<string, string>
     */
    private function getSlotsForAnyEmployee(
        Business $business,
        Service $service,
        string $date,
        int $dayOfWeek,
        int $duration,
        Carbon $bizOpen,
        Carbon $bizClose,
    ): array {
        $employees = $business->employees()
            ->where('is_active', true)
            ->whereHas('services', fn ($q) => $q->where('services.id', $service->id))
            ->get();

        if ($employees->isEmpty()) {
            return $this->generateSlots($bizOpen, $bizClose, $duration);
        }

        $allPossibleSlots = $this->generateSlots($bizOpen, $bizClose, $duration);

        return collect($allPossibleSlots)->filter(function (string $label, string $time) use ($employees, $dayOfWeek, $date, $duration): bool {
            $slotStart = Carbon::parse("{$date} {$time}");
            $slotEnd = $slotStart->copy()->addMinutes($duration);

            foreach ($employees as $employee) {
                if ($this->isEmployeeAvailableAtSlot($employee, $dayOfWeek, $date, $slotStart, $slotEnd)) {
                    return true;
                }
            }

            return false;
        })->all();
    }

    private function isEmployeeAvailableAtSlot(
        Employee $employee,
        int $dayOfWeek,
        string $date,
        Carbon $slotStart,
        Carbon $slotEnd,
    ): bool {
        $schedule = $employee->schedules()->where('day_of_week', $dayOfWeek)->first();

        if ($schedule && ! $schedule->is_active) {
            return false;
        }

        if ($schedule) {
            $empStart = Carbon::parse("{$date} {$schedule->start_time}");
            $empEnd = Carbon::parse("{$date} {$schedule->end_time}");

            if ($slotStart < $empStart || $slotEnd > $empEnd) {
                return false;
            }
        }

        $hasConflict = Appointment::where('employee_id', $employee->id)
            ->whereDate('starts_at', $date)
            ->where('status', '!=', AppointmentStatus::Cancelled)
            ->where('starts_at', '<', $slotEnd)
            ->where('ends_at', '>', $slotStart)
            ->exists();

        return ! $hasConflict;
    }

    /**
     * @return array<string, string>
     */
    private function generateSlots(Carbon $start, Carbon $end, int $duration): array
    {
        $slots = [];
        $current = $start->copy();
        $lastSlot = $end->copy()->subMinutes($duration);
        $now = Carbon::now();

        while ($current <= $lastSlot) {
            if ($current > $now) {
                $time = $current->format('H:i');
                $slots[$time] = $current->format('g:i A');
            }

            $current->addMinutes(15);
        }

        return $slots;
    }

    /**
     * @param  array<string, string>  $slots
     * @param  Collection<int, Appointment>  $appointments
     * @return array<string, string>
     */
    private function removeConflicts(array $slots, Collection $appointments, string $date, int $duration): array
    {
        if ($appointments->isEmpty()) {
            return $slots;
        }

        return collect($slots)->filter(function (string $label, string $time) use ($appointments, $date, $duration): bool {
            $slotStart = Carbon::parse("{$date} {$time}");
            $slotEnd = $slotStart->copy()->addMinutes($duration);

            foreach ($appointments as $appointment) {
                $appStart = Carbon::parse($appointment->starts_at);
                $appEnd = Carbon::parse($appointment->ends_at);

                if ($slotStart < $appEnd && $slotEnd > $appStart) {
                    return false;
                }
            }

            return true;
        })->all();
    }
}
