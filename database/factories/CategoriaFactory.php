<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement([
                'Tarjetas de Visita',
                'Flyers',
                'Pósters',
                'Banners',
                'Folletos',
                'Catálogos',
                'Etiquetas',
                'Invitaciones',
                'Calendarios',
                'Libretas'
            ]),
        ];
    }
}
