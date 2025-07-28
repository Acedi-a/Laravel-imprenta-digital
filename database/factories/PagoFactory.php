<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
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
            'monto' => fake()->randomFloat(2, 10, 1000),
            'metodo' => fake()->randomElement(['tarjeta_credito', 'tarjeta_debito', 'transferencia', 'efectivo', 'paypal']),
            'fecha_pago' => fake()->dateTimeBetween('-30 days', 'now'),
            'estado' => fake()->randomElement(['pendiente', 'completado', 'fallido', 'cancelado']),
            'referencia' => Str::uuid(),
            'comprobante_url' => fake()->optional()->url(),
        ];
    }

    

    /**
     * Indicate that the payment is completed.
     */
    public function completado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'completado',
        ]);
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'pendiente',
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function fallido(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'fallido',
        ]);
    }

    /**
     * Indicate that the payment is by credit card.
     */
    public function tarjetaCredito(): static
    {
        return $this->state(fn (array $attributes) => [
            'metodo' => 'tarjeta_credito',
        ]);
    }

    /**
     * Indicate that the payment is by cash.
     */
    public function efectivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'metodo' => 'efectivo',
        ]);
    }
}
