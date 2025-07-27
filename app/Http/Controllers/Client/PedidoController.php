<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Cotizacion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cotizacion.producto', 'cotizacion.archivo'])
            ->whereHas('cotizacion', function($query) {
                $query->where('usuario_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Client.pedidos', compact('pedidos'));
    }

    public function detalle($id)
    {
        $pedido = Pedido::with(['cotizacion.producto', 'cotizacion.archivo'])
            ->whereHas('cotizacion', function($query) {
                $query->where('usuario_id', Auth::id());
            })
            ->findOrFail($id);

        $pagos = Pago::where('pedido_id', $id)->get();

        return view('Client.pedido-detalle', compact('pedido', 'pagos'));
    }

    public function seguimiento($id)
    {
        $pedido = Pedido::with(['cotizacion.producto'])
            ->whereHas('cotizacion', function($query) {
                $query->where('usuario_id', Auth::id());
            })
            ->findOrFail($id);

        // Obtener historial de estados si existe la relación
        $historial = [];
        if (method_exists($pedido, 'historialEstados')) {
            $historial = $pedido->historialEstados()->orderBy('cambiado_en', 'desc')->get();
        }

        return view('Client.pedido-seguimiento', compact('pedido', 'historial'));
    }

    public function crear(Request $request, $cotizacion)
    {
        // Debug para ver qué está llegando
        Log::info('Parámetro recibido en crear:', [
            'cotizacion' => $cotizacion,
            'tipo' => gettype($cotizacion),
            'es_numerico' => is_numeric($cotizacion)
        ]);

        // Validar que sea un número
        if (!is_numeric($cotizacion)) {
            Log::error('ID de cotización no numérico:', ['valor' => $cotizacion]);
            abort(400, 'ID de cotización inválido');
        }

        // Convertir a entero por seguridad
        $cotizacionId = (int) $cotizacion;

        // Buscar y validar la cotización
        $cotizacionModel = Cotizacion::where('usuario_id', Auth::id())
            ->where('estado', 'aprobada')
            ->findOrFail($cotizacionId);

        // Calcular prioridad según cantidad/precio
        $cantidad = $cotizacionModel->cantidad ?? 0;
        $precio = $cotizacionModel->precio_total ?? 0;
        if ($cantidad > 1000 || $precio > 1000) {
            $prioridad = 'alta';
        } elseif ($cantidad < 100 || $precio < 100) {
            $prioridad = 'baja';
        } else {
            $prioridad = 'media';
        }

        $pedido = new Pedido([
            'cotizacion_id' => $cotizacionModel->id,
            'numero_pedido' => Str::uuid()->toString(),
            'estado' => 'pendiente',
            'fecha_pedido' => now(),
            'prioridad' => $prioridad,
            'notas' => $request->input('notas'),
        ]);

        $pedido->save();

        return redirect()->route('client.pedido-detalle', $pedido->id)
            ->with('success', 'Pedido creado exitosamente');
    }
}