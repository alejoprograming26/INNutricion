<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Paciente;
use App\Models\Cita;
use App\Enums\TipoCita;
use App\Enums\EstadoCita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cita>
 */
class CitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Paciente::factory(),
            'nutritionist_id' => User::factory(),
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'duration_minutes' => fake()->randomElement([30, 45, 60]),
            'type' => fake()->randomElement(TipoCita::cases()),
            'status' => fake()->randomElement(EstadoCita::cases()),
            'timezone' => 'America/Caracas',
            'notes' => fake()->optional()->paragraph(),
            'cancellation_reason' => null,
            'price' => fake()->randomElement([30.00, 50.00, 75.00]),
        ];
    }
}
