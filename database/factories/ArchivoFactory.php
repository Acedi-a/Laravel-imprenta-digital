<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Archivo>
 */
class ArchivoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extensions = ['pdf', 'jpg', 'png', 'ai', 'psd', 'eps'];
        $extension = fake()->randomElement($extensions);
        
        return [
            'usuario_id' => Usuario::factory(),
            'nombre_original' => fake()->word() . '.' . $extension,
            'ruta_guardado' => 'uploads/' . fake()->uuid() . '.' . $extension,
            'tamaño_archivo' => fake()->randomFloat(2, 0.1, 50), // MB
            'tipo_mime' => match($extension) {
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'ai' => 'application/postscript',
                'psd' => 'image/vnd.adobe.photoshop',
                'eps' => 'application/postscript',
                default => 'application/octet-stream'
            },
        ];
    }

    /**
     * Indicate that the file is a PDF.
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre_original' => fake()->word() . '.pdf',
            'ruta_guardado' => 'uploads/' . fake()->uuid() . '.pdf',
            'tipo_mime' => 'application/pdf',
        ]);
    }

    /**
     * Indicate that the file is an image.
     */
    public function imagen(): static
    {
        $extension = fake()->randomElement(['jpg', 'png']);
        
        return $this->state(fn (array $attributes) => [
            'nombre_original' => fake()->word() . '.' . $extension,
            'ruta_guardado' => 'uploads/' . fake()->uuid() . '.' . $extension,
            'tipo_mime' => $extension === 'jpg' ? 'image/jpeg' : 'image/png',
        ]);
    }
}
