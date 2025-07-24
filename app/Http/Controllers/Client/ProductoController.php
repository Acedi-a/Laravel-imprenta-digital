<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function detalle($id)
    {
        $producto = Producto::with(['tamanoPapel.fotosReferenciales'])->findOrFail($id);

        $productosRelacionados = Producto::where('tamano_papel_id', $producto->tamano_papel_id)
            ->where('id', '!=', $id)
            ->where('estado', 'activo')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('Client.producto-detalle', [
            'producto' => $producto,
            'productosRelacionados' => $productosRelacionados
        ]);
    }

}
