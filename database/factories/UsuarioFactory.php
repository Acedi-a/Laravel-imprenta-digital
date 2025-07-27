<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $array_contraseñas = [
            'admin123',
            'cliente123'
        ];
        static $index = 0;

        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make($array_contraseñas[$index++ % count($array_contraseñas)]),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'rol' => fake()->randomElement(['admin', 'cliente']),
            'telefono' => fake()->phoneNumber(),
            'email_verified_at' => now(),                
            'remember_token' => Str::random(10),
            'estado' => fake()->randomElement(['activo', 'inactivo']),
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
