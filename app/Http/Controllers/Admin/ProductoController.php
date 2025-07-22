<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('id','desc')->paginate(20);
        return view('Admin.Productos.Index', compact('productos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'categoria'   => 'required|string|max:255',
            'precio'      => 'required|numeric|min:0',
            'tipo_unidad' => 'required|string|max:50',
            'ancho_max'   => 'required|numeric|min:0',
            'alto_max'    => 'required|numeric|min:0',
            'estado'      => 'required|in:activo,inactivo,agotado',
            'descripcion' => 'nullable|string',
        ]);

        Producto::create($request->all());
        return redirect()->route('admin.productos.index')->with('success', 'Producto creado.');
    }

    public function actualizar(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'categoria'   => 'required|string|max:255',
            'precio'      => 'required|numeric|min:0',
            'tipo_unidad' => 'required|string|max:50',
            'ancho_max'   => 'required|numeric|min:0',
            'alto_max'    => 'required|numeric|min:0',
            'estado'      => 'required|in:activo,inactivo,agotado',
                'descripcion' => 'nullable|string',
        ]);

        $producto->update($request->all());
        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado.');
    }

    public function eliminar(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado.');
    }
}