<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Direccion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Envio>
 */
class EnvioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaEnvio = fake()->dateTimeBetween('-15 days', 'now');
        $fechaEstimada = fake()->dateTimeBetween($fechaEnvio, '+10 days');
        
        return [
            'pedido_id' => Pedido::factory(),
            'direccion_id' => Direccion::factory(),
            'transportista' => fake()->randomElement(['DHL', 'FedEx', 'UPS', 'Correos', 'MRW', 'SEUR']),
            'codigo_seguimiento' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'fecha_envio' => $fechaEnvio,
            'fecha_estimada_entrega' => $fechaEstimada,
        ];
    }

    /**
     * Indicate that the shipment is via DHL.
     */
    public function dhl(): static
    {
        return $this->state(fn (array $attributes) => [
            'transportista' => 'DHL',
            'codigo_seguimiento' => fake()->unique()->regexify('[0-9]{10}'),
        ]);
    }

    /**
     * Indicate that the shipment is via FedEx.
     */
    public function fedex(): static
    {
        return $this->state(fn (array $attributes) => [
            'transportista' => 'FedEx',
            'codigo_seguimiento' => fake()->unique()->regexify('[0-9]{12}'),
        ]);
    }

    /**
     * Indicate that the shipment is via UPS.
     */
    public function ups(): static
    {
        return $this->state(fn (array $attributes) => [
            'transportista' => 'UPS',
            'codigo_seguimiento' => fake()->unique()->regexify('1Z[A-Z0-9]{16}'),
        ]);
    }
}
