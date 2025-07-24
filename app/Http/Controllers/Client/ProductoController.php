<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OpcionProducto;
use App\Models\Producto; 
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function detalle($id)
    {
        // Obtener producto con sus relaciones
        $producto = Producto::findOrFail($id);
        
        // Obtener opciones del producto
        $opciones = OpcionProducto::where('producto_id', $id)
                    ->orderBy('orden')
                    ->get();
        
        // Productos relacionados (misma categoría)
        $productosRelacionados = Producto::where('categoria', $producto->categoria)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('cliente.producto-detalle', [
            'producto' => $producto,
            'opciones' => $opciones,
            'productosRelacionados' => $productosRelacionados
        ]);
    }
}