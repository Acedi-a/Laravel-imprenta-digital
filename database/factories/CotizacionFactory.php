<?php

namespace Database\Factories;

use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Archivo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CotizacionFactory extends Factory
{
    protected $model = \App\Models\Cotizacion::class;

    public function definition()
    {
        $cantidad = $this->faker->numberBetween(1, 1000);
        $precioUnitario = $this->faker->randomFloat(2, 0.5, 100);

        return [
            'usuario_id' => Usuario::factory(),
            'producto_id' => Producto::factory(),
            'archivo_id' => Archivo::factory(),
            'cantidad' => $cantidad,
            'precio_total' => $cantidad * $precioUnitario,
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada', 'vencida']),
        ];
    }
}
