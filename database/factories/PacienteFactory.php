<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Paciente;
use App\Enums\Genero;
use App\Enums\EstadoPaciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nutritionist_id' => User::factory(),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(Genero::cases()),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'occupation' => fake()->jobTitle(),
            'address' => fake()->address(),
            'emergency_contact' => [
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'relationship' => fake()->randomElement(['Padre', 'Madre', 'Hijo/a', 'Pareja']),
            ],
            'referred_by' => fake()->name(),
            'status' => fake()->randomElement(EstadoPaciente::cases()),
        ];
    }
}
