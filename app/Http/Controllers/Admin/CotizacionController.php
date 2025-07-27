<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Archivo;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with(['usuario','producto.tamanoPapel','archivo',])->orderBy('id', 'desc')->paginate(20);

        $usuarios = Usuario::all();
        $productos = Producto::all();
        $archivos = Archivo::all();

        return view('Admin.Cotizaciones.index', compact('cotizaciones', 'usuarios', 'productos', 'archivos'));
    }

    public function detalle($id)
    {
        $cotizacion = Cotizacion::with([
            'usuario',
            'producto.tamanoPapel',
            'archivo',
        ])->findOrFail($id);
        return view('Admin.Cotizaciones.detalle', compact('cotizacion'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'usuario_id'   => 'required|exists:usuarios,id',
            'producto_id'  => 'required|exists:productos,id',
            'archivo_id'   => 'required|exists:archivos,id',
            'cantidad'     => 'required|integer|min:1',
            'precio_total' => 'required|numeric|min:0',
            'estado'       => 'required|in:pendiente,aprobada,cancelada',
        ]);

        Cotizacion::create($request->only([
            'usuario_id',
            'producto_id',
            'archivo_id',
            'cantidad',
            'precio_total',
            'estado',
        ]));
        return redirect()->route('admin.cotizaciones.index')->with('success', 'Cotización guardada.');
    }

    public function actualizar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'usuario_id'   => 'required|exists:usuarios,id',
            'producto_id'  => 'required|exists:productos,id',
            'archivo_id'   => 'required|exists:archivos,id',
            'cantidad'     => 'required|integer|min:1',
            'precio_total' => 'required|numeric|min:0',
            'estado'       => 'required|in:pendiente,aprobada,cancelada',
        ]);

        $cotizacion->update($request->only([
            'usuario_id',
            'producto_id',
            'archivo_id',
            'cantidad',
            'precio_total',
            'estado',
        ]));
        return redirect()->route('admin.cotizaciones.index')->with('success', 'Cotización actualizada.');
    }

    public function eliminar(Cotizacion $cotizacion)
    {
        $nuevoEstado = $cotizacion->estado === 'rechazada' ? 'pendiente' : 'rechazada';
        $cotizacion->update(['estado' => $nuevoEstado]);
        return back()->with('success', 'Estado de cotización cambiado.');
    }
    
}
