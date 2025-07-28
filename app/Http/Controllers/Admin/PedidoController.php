<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Cotizacion;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos     = Pedido::with('cotizacion')->orderBy('id', 'desc')->paginate(20);
        $cotizaciones = Cotizacion::all();
        return view('Admin.Pedidos.index', compact('pedidos', 'cotizaciones'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'cotizacion_id'  => 'required|exists:cotizaciones,id',
            'numero_pedido'  => 'required|string|unique:pedidos,numero_pedido',
            'estado'         => 'required|in:en_proceso,finalizado,cancelado',
            'notas'          => 'nullable|string|max:1000',
        ]);

        $cotizacion = Cotizacion::findOrFail($request->cotizacion_id);
        $cantidad = $cotizacion->cantidad ?? 0;
        $precio = $cotizacion->precio_total ?? 0;
        if ($cantidad > 1000 || $precio > 1000) {
            $prioridad = 'alta';
        } elseif ($cantidad < 100 || $precio < 100) {
            $prioridad = 'baja';
        } else {
            $prioridad = 'media';
        }

        Pedido::create(array_merge(
            $request->all(),
            [
                'fecha_pedido' => now(),
                'prioridad' => $prioridad
            ]
        ));

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido guardado.');
    }

    public function actualizar(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado'         => 'required|in:en_proceso,finalizado,cancelado',
            'prioridad'      => 'required|in:baja,media,alta',
            'notas'          => 'nullable|string|max:1000',
        ]);

        $pedido->update([
            'estado'    => $request->estado,
            'prioridad' => $request->prioridad,
            'notas'     => $request->notas,
        ]);

        // Si el estado es 'finalizado' y el pedido no tiene envío, crearlo automáticamente
        if ($request->estado === 'finalizado' && !$pedido->envio) {
            // Usar la dirección del pedido o buscar una dirección predeterminada como fallback
            $direccionId = null;
            
            if ($pedido->direccion_id) {
                // Usar la dirección seleccionada en el pedido
                $direccionId = $pedido->direccion_id;
            } else {
                // Fallback: buscar dirección predeterminada del cliente
                $cotizacion = $pedido->cotizacion;
                $usuario = $cotizacion ? $cotizacion->usuario : null;
                $direccion = $usuario ? $usuario->direcciones()->where('defecto', true)->first() : null;
                if (!$direccion && $usuario) {
                    $direccion = $usuario->direcciones()->first();
                }
                $direccionId = $direccion ? $direccion->id : null;
            }
            
            if ($direccionId) {
                \App\Models\Envio::create([
                    'pedido_id' => $pedido->id,
                    'direccion_id' => $direccionId,
                    'transportista' => 'Por asignar',
                    'codigo_seguimiento' => 'Por asignar',
                    'fecha_envio' => now(),
                    'fecha_estimada_entrega' => now()->addDays(3),
                    'estado' => 'pendiente',
                ]);
                
                // Crear notificación para el cliente
                \App\Models\Notificacion::create([
                    'usuario_id' => $pedido->cotizacion->usuario_id,
                    'titulo' => 'Envío creado',
                    'mensaje' => "Se ha creado el envío para tu pedido #{$pedido->numero_pedido}. Puedes hacer seguimiento desde tu panel.",
                    'tipo' => 'envio',
                    'leido' => false,
                ]);
            }
        }
        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido actualizado.');
    }

    public function eliminar(Pedido $pedido)
    {
        $nuevoEstado = $pedido->estado === 'cancelado' ? 'en_proceso' : 'cancelado';
        $pedido->update(['estado' => $nuevoEstado]);
        return back()->with('success', 'Estado del pedido cambiado.');
    }

    // Detalle de pedido con info de pago y PDF preview
    public function detalle($id)
    {
        $pedido = \App\Models\Pedido::with([
            'cotizacion.usuario',
            'cotizacion.producto',
            'pago',
        ])->findOrFail($id);
        $pago = $pedido->pago;
        $pdfUrl = null;
        if ($pago && $pago->comprobante_url) {
            $pdfUrl = route('admin.pedidos.pdfpreview', ['id' => $pedido->id]);
        }
        return view('Admin.Pedidos.detalle', compact('pedido', 'pago', 'pdfUrl'));
    }

    // Preview PDF inline (stream)
    public function pdfPreview($id)
    {
        $pedido = \App\Models\Pedido::with(['pago','cotizacion.usuario'])->findOrFail($id);
        $pago = $pedido->pago;
        $pdfPath = $pago && $pago->comprobante_url ? $pago->comprobante_url : null;
        if (!$pdfPath || !\Illuminate\Support\Facades\Storage::exists($pdfPath)) {
            abort(404);
        }
        $pdfContent = \Illuminate\Support\Facades\Storage::get($pdfPath);
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ticket-pago.pdf"',
        ]);
    }
}
