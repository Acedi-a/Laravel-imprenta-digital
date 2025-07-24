<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\TamanoPapel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'tipo_impresion' => $this->faker->randomElement(['Digital', 'Offset', 'Gran formato']),
            'tipo_papel' => $this->faker->randomElement(['Couché', 'Bond', 'Opalina']),
            'acabado' => $this->faker->randomElement(['Laminado', 'Troquelado', 'Barniz', null]),
            'color' => $this->faker->randomElement(['Color', 'Blanco y negro']),
            'tamano_papel_id' => TamanoPapel::factory(), // Relación
            'cantidad_minima' => $this->faker->numberBetween(1, 100),
            'precio_base' => $this->faker->randomFloat(2, 10, 500),
            'descuento' => $this->faker->randomFloat(2, 0, 50),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
