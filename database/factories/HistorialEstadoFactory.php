<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistorialEstado>
 */
class HistorialEstadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pedido_id' => Pedido::factory(),
            'estado' => fake()->randomElement(['pendiente', 'en_proceso', 'completado', 'cancelado']),
            'cambiado_por' => Usuario::factory(),
            'cambiado_en' => fake()->dateTimeBetween('-30 days', 'now'),
            'notas' => fake()->optional()->text(100),
        ];
    }

    /**
     * Indicate that the status was changed to pending.
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'pendiente',
            'notas' => 'Pedido creado y en espera de procesamiento',
        ]);
    }

    /**
     * Indicate that the status was changed to in process.
     */
    public function enProceso(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'en_proceso',
            'notas' => 'Pedido en proceso de producción',
        ]);
    }

    /**
     * Indicate that the status was changed to completed.
     */
    public function completado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'completado',
            'notas' => 'Pedido completado y listo para entrega',
        ]);
    }

    /**
     * Indicate that the status was changed to canceled.
     */
    public function cancelado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'cancelado',
            'notas' => 'Pedido cancelado por el cliente',
        ]);
    }
}
