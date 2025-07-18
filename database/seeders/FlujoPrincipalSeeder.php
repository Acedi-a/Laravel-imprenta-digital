<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Archivo;
use App\Models\Cotizacion;
use App\Models\Pedido;
use App\Models\Direccion;
use App\Models\Envio;
use App\Models\Pago;
use App\Models\HistorialEstado;
use App\Models\Notificacion;

class FlujoPrincipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder crea el flujo completo de la imprenta:
     * Usuario → Cotización → Pedido → Envío
     * Con todas las entidades relacionadas
     */
    public function run(): void
    {
        // Crear 5 usuarios clientes
        $usuarios = Usuario::factory()
            ->count(5)
            ->create();

        // Crear productos disponibles
        $productos = Producto::factory()
            ->count(10)
            ->create();

        foreach ($usuarios as $usuario) {
            // Crear direcciones para el usuario
            $direcciones = Direccion::factory()
                ->count(2)
                ->create();

            $cotizaciones = Cotizacion::factory()
                ->count(rand(2, 3))
                ->create([
                    'usuario_id' => $usuario->id,
                    'producto_id' => $productos->random()->id,
                    'archivo_id' => Archivo::factory()->create()->id,
                ]);

            foreach ($cotizaciones as $cotizacion) {
                if ($cotizacion->estado === 'aprobada' || rand(1, 3) === 1) {
                    $cotizacion->update(['estado' => 'aprobada']);
                    
                    // Crear pedido basado en la cotización
                    $pedido = Pedido::factory()
                        ->create([
                            'cotizacion_id' => $cotizacion->id,
                        ]);

                    // Crear pago para el pedido
                    $pago = Pago::factory()
                        ->create([
                            'pedido_id' => $pedido->id,
                            'monto' => $cotizacion->precio_total,
                        ]);

                    // Crear historial de estados para el pedido
                    $estadosHistorial = ['pendiente', 'en_proceso', 'completado'];
                    foreach ($estadosHistorial as $index => $estado) {
                        HistorialEstado::factory()
                            ->create([
                                'pedido_id' => $pedido->id,
                                'estado' => $estado,
                                'created_at' => now()->addDays($index),
                            ]);
                    }

                    // Si el pedido está completado, crear envío
                    if ($pedido->estado === 'completado' || rand(1, 2) === 1) {
                        $envio = Envio::factory()
                            ->create([
                                'pedido_id' => $pedido->id,
                                'direccion_id' => $direcciones->random()->id,
                            ]);

                        // Crear notificación de envío
                        Notificacion::factory()
                            ->create([
                                'usuario_id' => $usuario->id,
                                'mensaje' => "Tu pedido #{$pedido->numero_pedido} ha sido enviado. Código de seguimiento: {$envio->codigo_seguimiento}",
                                'tipo' => 'envio',
                            ]);
                    }

                    // Crear notificaciones del proceso
                    Notificacion::factory()
                        ->create([
                            'usuario_id' => $usuario->id,
                            'mensaje' => "Tu cotización ha sido aprobada y convertida en pedido #{$pedido->numero_pedido}",
                            'tipo' => 'pedido',
                        ]);
                }
            }

            // Crear notificación de bienvenida
            Notificacion::factory()
                ->create([
                    'usuario_id' => $usuario->id,
                    'mensaje' => "¡Bienvenido a nuestro sistema de imprenta, {$usuario->nombre}!",
                    'tipo' => 'bienvenida',
                ]);
        }

        $this->command->info('✅ Flujo principal completado:');
        $this->command->info("   - {$usuarios->count()} usuarios creados");
        $this->command->info("   - {$productos->count()} productos creados");
        $this->command->info("   - " . Cotizacion::count() . " cotizaciones creadas");
        $this->command->info("   - " . Pedido::count() . " pedidos creados");
        $this->command->info("   - " . Envio::count() . " envíos creados");
        $this->command->info("   - " . Pago::count() . " pagos creados");
        $this->command->info("   - " . Notificacion::count() . " notificaciones creadas");
    }
}
