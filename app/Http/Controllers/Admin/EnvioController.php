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
        $envios     = Envio::with(['pedido','direccion'])->orderBy('id','desc')->paginate(20);
        $pedidos    = Pedido::where('estado','!=','cancelado')->get();
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
            'transportista'=> 'required|string|max:100',
            'codigo_seguimiento' => 'required|string|max:100',
            'fecha_envio' => 'required|date',
            'fecha_estimada_entrega' => 'required|date|after:fecha_envio',
            'estado' => 'required|in:pendiente,en_camino,entregado,cancelado',
        ]);

        $envio->update($request->only([
            'transportista',
            'codigo_seguimiento',
            'fecha_envio',
            'fecha_estimada_entrega',
            'estado',
        ]));

        // Actualizar estado del pedido según el estado del envío
        $pedido = $envio->pedido;
        if ($request->estado === 'en_camino' && $pedido->estado !== 'en_camino') {
            $pedido->update(['estado' => 'en_camino']);
        } elseif ($request->estado === 'entregado' && $pedido->estado !== 'entregado') {
            $pedido->update(['estado' => 'entregado']);
        }

        // Notificar al usuario del pedido sobre actualización de envío
        if ($pedido && $pedido->cotizacion && $pedido->cotizacion->usuario_id) {
            Notificacion::create([
                'usuario_id' => $pedido->cotizacion->usuario_id,
                'tipo' => 'envio',
                'titulo' => 'Actualización de envío',
                'mensaje' => 'El envío del pedido #' . $pedido->numero_pedido . ' ha sido actualizado. Revisa los nuevos datos de seguimiento.',
                'leido' => false,
            ]);
        }
        return redirect()->route('admin.envios.index')->with('success','Envío actualizado y notificación enviada.');
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