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
            'numero_pedido'  => 'required|integer|unique:pedidos,numero_pedido',
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
            'cotizacion_id'  => 'required|exists:cotizaciones,id',
            'numero_pedido'  => 'required|integer|unique:pedidos,numero_pedido,' . $pedido->id,
            'estado'         => 'required|in:en_proceso,finalizado,cancelado',
            'notas'          => 'nullable|string|max:1000',
        ]);

        $cotizacion = Cotizacion::findOrFail($request->cotizacion_id);
        $cantidad = $cotizacion->cantidad ?? 0;
        $precio = $cotizacion->precio ?? 0;
        if ($cantidad > 1000 || $precio > 1000) {
            $prioridad = 'alta';
        } elseif ($cantidad < 100 || $precio < 100) {
            $prioridad = 'baja';
        } else {
            $prioridad = 'media';
        }

        $pedido->update(array_merge(
            $request->all(),
            ['prioridad' => $prioridad]
        ));
        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido actualizado.');
    }

    public function eliminar(Pedido $pedido)
    {
        $nuevoEstado = $pedido->estado === 'cancelado' ? 'en_proceso' : 'cancelado';
        $pedido->update(['estado' => $nuevoEstado]);
        return back()->with('success', 'Estado del pedido cambiado.');
    }
}
