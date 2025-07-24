<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\TamanoPapel;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('tamanoPapel')->orderBy('id','desc')->paginate(20);
        $tamanosPapel = TamanoPapel::all();
        return view('Admin.Productos.Index', compact('productos', 'tamanosPapel'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'tipo_impresion'  => 'required|string|max:255',
            'tipo_papel'      => 'nullable|string|max:255',
            'acabado'         => 'nullable|string|max:255',
            'color'           => 'nullable|string|max:255',
            'tamano_papel_id' => 'nullable|exists:tamano_papel,id',
            'cantidad_minima' => 'required|integer|min:1',
            'precio_base'     => 'required|numeric|min:0',
            'descuento'       => 'nullable|numeric|min:0',
            'descripcion'     => 'nullable|string',
        ]);

        Producto::create($request->all());
        return redirect()->route('admin.productos.index')->with('success', 'Producto creado.');
    }

    public function actualizar(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'tipo_impresion'  => 'required|string|max:255',
            'tipo_papel'      => 'nullable|string|max:255',
            'acabado'         => 'nullable|string|max:255',
            'color'           => 'nullable|string|max:255',
            'tamano_papel_id' => 'nullable|exists:tamano_papel,id',
            'cantidad_minima' => 'required|integer|min:1',
            'precio_base'     => 'required|numeric|min:0',
            'descuento'       => 'nullable|numeric|min:0',
            'descripcion'     => 'nullable|string',
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