<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpcionProducto>
 */
class OpcionProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'nombre_opcion' => fake()->randomElement([
                'Tipo de Papel',
                'Acabado',
                'Color',
                'Tamaño',
                'Orientación',
                'Laminado',
                'Barniz',
                'Impresión'
            ]),
            'valor_opcion' => fake()->randomElement([
                'Mate',
                'Brillante',
                'Satinado',
                'Reciclado',
                'Premium',
                'Estándar',
                'A colores',
                'Blanco y Negro'
            ]),
            'ajuste_precio' => fake()->randomInt(0,100),
            'orden' => fake()->numberBetween(1, 10),
        ];
    }
}
