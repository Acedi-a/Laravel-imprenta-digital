<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direccion>
 */
class DireccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'linea1' => fake()->streetAddress(),
            'linea2' => fake()->optional()->secondaryAddress(),
            'ciudad' => fake()->city(),
            'codigo_postal' => fake()->postcode(),
            'pais' => fake()->country(),
            'defecto' => fake()->boolean(20), // 20% chance of being default
        ];
    }

    /**
     * Indicate that this is the default address.
     */
    public function defecto(): static
    {
        return $this->state(fn (array $attributes) => [
            'defecto' => true,
        ]);
    }
}
