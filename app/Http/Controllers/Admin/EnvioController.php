<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use App\Models\Pedido;
use App\Models\Direccion;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::with(['pedido.cotizacion.usuario', 'pedido.direccion'])->orderBy('id','desc')->paginate(20);
        $pedidos = Pedido::where('estado','!=','cancelado')->get();
        $direcciones = Direccion::all();
        return view('Admin.Envios.index', compact('envios','pedidos','direcciones'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'direccion_id'=> 'required|exists:direcciones,id',
            'transportista'=> 'required|string|max:100',
            'codigo_seguimiento' => 'required|string|max:100',
            'fecha_envio' => 'required|date',
            'fecha_estimada_entrega' => 'required|date|after:fecha_envio',
        ]);

        $envio = Envio::create($request->all());

        // Notificar al usuario del pedido
        $pedido = Pedido::find($request->pedido_id);
        if ($pedido && $pedido->cotizacion && $pedido->cotizacion->usuario_id) {
            Notificacion::create([
                'usuario_id' => $pedido->cotizacion->usuario_id,
                'tipo' => 'envio',
                'titulo' => '¡Tu pedido ha sido enviado!',
                'mensaje' => 'El pedido #' . $pedido->numero_pedido . ' ha sido despachado. Puedes hacer seguimiento en tu panel de usuario.',
                'leido' => false,
            ]);
        }
        return redirect()->route('admin.envios.index')->with('success','Envío guardado y notificación enviada.');
    }

    public function actualizar(Request $request, Envio $envio)
    {
        $request->validate([
            'transportista' => 'required|string|max:100',
            'fecha_estimada_entrega' => 'required|date|after_or_equal:today',
            'estado' => 'required|in:pendiente,en_camino,entregado,cancelado',
        ]);

        $estadoAnterior = $envio->estado;
        $nuevoEstado = $request->estado;
        
        // Datos a actualizar
        $datosActualizacion = [
            'transportista' => $request->transportista,
            'fecha_estimada_entrega' => $request->fecha_estimada_entrega,
            'estado' => $nuevoEstado,
        ];

        // Si cambia de 'pendiente' a 'en_camino', establecer fecha_envio automáticamente
        if ($estadoAnterior === 'pendiente' && $nuevoEstado === 'en_camino') {
            $datosActualizacion['fecha_envio'] = now();
        }

        $envio->update($datosActualizacion);

        // Actualizar estado del pedido según el estado del envío
        $pedido = $envio->pedido;
        if ($nuevoEstado === 'en_camino' && $pedido->estado !== 'en_camino') {
            $pedido->update(['estado' => 'en_camino']);
        } elseif ($nuevoEstado === 'entregado' && $pedido->estado !== 'entregado') {
            $pedido->update(['estado' => 'entregado']);
        } elseif ($nuevoEstado === 'cancelado' && $pedido->estado !== 'cancelado') {
            $pedido->update(['estado' => 'cancelado']);
        }

        // Crear notificación según el cambio de estado
        if ($pedido && $pedido->cotizacion && $pedido->cotizacion->usuario_id) {
            $titulo = '';
            $mensaje = '';
            
            switch ($nuevoEstado) {
                case 'en_camino':
                    $titulo = '🚛 Tu pedido está en camino';
                    $mensaje = "¡Excelente! Tu pedido #{$pedido->numero_pedido} ya está en camino. El transportista {$request->transportista} se encarga de la entrega.";
                    break;
                case 'entregado':
                    $titulo = '✅ Pedido entregado';
                    $mensaje = "Tu pedido #{$pedido->numero_pedido} ha sido entregado exitosamente. ¡Gracias por confiar en nosotros!";
                    break;
                case 'cancelado':
                    $titulo = '❌ Envío cancelado';
                    $mensaje = "El envío de tu pedido #{$pedido->numero_pedido} ha sido cancelado. Te contactaremos para coordinar una nueva entrega.";
                    break;
                default:
                    $titulo = 'Actualización de envío';
                    $mensaje = "Se ha actualizado la información del envío de tu pedido #{$pedido->numero_pedido}.";
            }
            
            Notificacion::create([
                'usuario_id' => $pedido->cotizacion->usuario_id,
                'tipo' => 'envio',
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'leido' => false,
            ]);
        }

        return redirect()->route('admin.envios.index')->with('success', 'Envío actualizado y notificación enviada.');
    }

    public function eliminar(Envio $envio)
    {
        $nuevoEstado = $envio->estado === 'cancelado' ? 'activo' : 'cancelado';
        $envio->update(['estado' => $nuevoEstado]);

        // Notificar al usuario del pedido sobre cancelación/reactivación de envío
        $pedido = $envio->pedido;
        if ($pedido && $pedido->cotizacion && $pedido->cotizacion->usuario_id) {
            Notificacion::create([
                'usuario_id' => $pedido->cotizacion->usuario_id,
                'tipo' => 'envio',
                'titulo' => $nuevoEstado === 'cancelado' ? 'Envío cancelado' : 'Envío reactivado',
                'mensaje' => $nuevoEstado === 'cancelado'
                    ? 'El envío de tu pedido #' . $pedido->numero_pedido . ' ha sido cancelado.'
                    : 'El envío de tu pedido #' . $pedido->numero_pedido . ' ha sido reactivado.',
                'leido' => false,
            ]);
        }
        return back()->with('success','Estado del envío cambiado y notificación enviada.');
    }
}