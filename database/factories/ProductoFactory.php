<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria' => fake()->randomElement([
                'Tarjetas de Visita',
                'Flyers',
                'Pósters',
                'Banners',
                'Folletos',
                'Catálogos',
                'Etiquetas',
                'Invitaciones'
            ]),
            'nombre' => fake()->words(3, true),
            'precio' => fake()->randomFloat(2, 5, 500),
            'tipo_unidad' => fake()->randomElement(['unidad', 'metro', 'centímetro', 'pulgada']),
            'ancho_max' => fake()->randomFloat(2, 10, 200),
            'alto_max' => fake()->randomFloat(2, 10, 200),
            'estado' => fake()->randomElement(['activo', 'inactivo', 'agotado']),
            'descripcion' => fake()->text(200),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'activo',
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function agotado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'agotado',
        ]);
    }
}
