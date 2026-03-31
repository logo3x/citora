<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('+1 day', '+30 days');
        $durationMinutes = fake()->randomElement([30, 45, 60, 90]);

        return [
            'business_id' => Business::factory(),
            'service_id' => Service::factory(),
            'employee_id' => Employee::factory(),
            'customer_id' => User::factory(),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify("+{$durationMinutes} minutes"),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (array $attributes) => [
            'business_id' => $business->id,
            'service_id' => Service::factory()->state(['business_id' => $business->id]),
            'employee_id' => Employee::factory()->state(['business_id' => $business->id]),
        ]);
    }
}
