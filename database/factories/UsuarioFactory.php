<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'rol' => fake()->randomElement(['admin', 'cliente', 'empleado']),
            'telefono' => fake()->phoneNumber(),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a client.
     */
    public function cliente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'cliente',
        ]);
    }

    /**
     * Indicate that the user is an employee.
     */
    public function empleado(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'empleado',
        ]);
    }
}
