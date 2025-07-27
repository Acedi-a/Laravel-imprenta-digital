<?php

namespace Database\Factories;

use App\Models\Cotizacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cotizacion_id' => Cotizacion::factory(),
            'numero_pedido' => fake()->uuid(),
            'estado' => fake()->randomElement(['pendiente', 'en_proceso', 'completado', 'cancelado']),
            'prioridad' => fake()->randomElement(['baja', 'media', 'alta', 'urgente']),
            'notas' => fake()->optional()->text(200),
            'fecha_pedido' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'pendiente',
        ]);
    }

    /**
     * Indicate that the order is in process.
     */
    public function enProceso(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'en_proceso',
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'completado',
        ]);
    }

    /**
     * Indicate that the order is canceled.
     */
    public function cancelado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'cancelado',
        ]);
    }

    /**
     * Indicate that the order has high priority.
     */
    public function prioridadAlta(): static
    {
        return $this->state(fn (array $attributes) => [
            'prioridad' => 'alta',
        ]);
    }

    /**
     * Indicate that the order has urgent priority.
     */
    public function urgente(): static
    {
        return $this->state(fn (array $attributes) => [
            'prioridad' => 'urgente',
        ]);
    }
}
