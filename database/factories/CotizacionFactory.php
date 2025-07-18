<?php

namespace Database\Factories;

use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Archivo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cotizacion>
 */
class CotizacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cantidad = fake()->numberBetween(1, 1000);
        $precioUnitario = fake()->randomFloat(2, 0.5, 100);
        
        return [
            'usuario_id' => Usuario::factory(),
            'producto_id' => Producto::factory(),
            'archivo_id' => Archivo::factory(),
            'cantidad' => $cantidad,
            'ancho' => fake()->randomFloat(2, 5, 50),
            'alto' => fake()->randomFloat(2, 5, 50),
            'precio_total' => $cantidad * $precioUnitario,
            'estado' => fake()->randomElement(['pendiente', 'aprobada', 'rechazada', 'vencida']),
        ];
    }

    /**
     * Indicate that the quotation is pending.
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'pendiente',
        ]);
    }

    /**
     * Indicate that the quotation is approved.
     */
    public function aprobada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'aprobada',
        ]);
    }

    /**
     * Indicate that the quotation is rejected.
     */
    public function rechazada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'rechazada',
        ]);
    }

    /**
     * Indicate that the quotation is expired.
     */
    public function vencida(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'vencida',
        ]);
    }
}
