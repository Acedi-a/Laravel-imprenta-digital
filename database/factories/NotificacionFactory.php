<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notificacion>
 */
class NotificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'tipo' => fake()->randomElement(['pedido', 'pago', 'envio', 'sistema', 'promocion']),
            'titulo' => fake()->sentence(4),
            'mensaje' => fake()->text(150),
            'leido' => fake()->boolean(30), // 30% chance of being read
        ];
    }

    /**
     * Indicate that the notification is read.
     */
    public function leida(): static
    {
        return $this->state(fn (array $attributes) => [
            'leido' => true,
        ]);
    }

    /**
     * Indicate that the notification is unread.
     */
    public function noLeida(): static
    {
        return $this->state(fn (array $attributes) => [
            'leido' => false,
        ]);
    }

    /**
     * Indicate that the notification is about an order.
     */
    public function pedido(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'pedido',
            'titulo' => 'Actualización de pedido',
            'mensaje' => 'Su pedido ha sido actualizado. Revise los detalles.',
        ]);
    }

    /**
     * Indicate that the notification is about a payment.
     */
    public function pago(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'pago',
            'titulo' => 'Confirmación de pago',
            'mensaje' => 'Su pago ha sido procesado exitosamente.',
        ]);
    }

    /**
     * Indicate that the notification is about a shipment.
     */
    public function envio(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'envio',
            'titulo' => 'Producto enviado',
            'mensaje' => 'Su pedido ha sido enviado y está en camino.',
        ]);
    }

    /**
     * Indicate that the notification is a system notification.
     */
    public function sistema(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'sistema',
            'titulo' => 'Notificación del sistema',
            'mensaje' => 'Información importante sobre su cuenta.',
        ]);
    }

    /**
     * Indicate that the notification is a promotion.
     */
    public function promocion(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'promocion',
            'titulo' => 'Oferta especial',
            'mensaje' => 'Aprovecha nuestras ofertas especiales en productos de imprenta.',
        ]);
    }
}
