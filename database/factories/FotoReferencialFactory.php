<?php

namespace Database\Factories;

use App\Models\FotoReferencial;
use App\Models\TamanoPapel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FotoReferencialFactory extends Factory
{
    protected $model = FotoReferencial::class;
    
    public function definition()
    {
        return [
            'tamano_papel_id' => TamanoPapel::factory(),
            'url' => $this->faker->imageUrl(640, 480, 'business', true, 'Mockup'),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
