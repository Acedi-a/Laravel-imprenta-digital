<?php

namespace Database\Factories;

use App\Models\TamanoPapel;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FotoReferencial;

class TamanoPapelFactory extends Factory
{
    protected $model = TamanoPapel::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->randomElement(['A4', 'Carta', 'Oficio', 'Tabloide']),
            'alto' => $this->faker->randomElement(['210', '216', '279', '432']),
            'ancho' => $this->faker->randomElement(['297', '279', '330', '432']),
            'descripcion' => $this->faker->sentence(),
            'unidad_medida' => $this->faker->randomElement(['mm', 'cm', 'in']),
        ];
    }

 

}
