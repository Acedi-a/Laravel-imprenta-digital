<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\TamanoPapel;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('tamanoPapel')
            ->where('estado', 'activo');

        // Filtros
        if ($request->has('tipo_impresion') && $request->tipo_impresion != '') {
            $query->where('tipo_impresion', $request->tipo_impresion);
        }

        if ($request->has('tipo_papel') && $request->tipo_papel != '') {
            $query->where('tipo_papel', $request->tipo_papel);
        }

        if ($request->has('tamano_papel_id') && $request->tamano_papel_id != '') {
            $query->where('tamano_papel_id', $request->tamano_papel_id);
        }

        // Ordenamiento
        $ordenamiento = $request->orden ?? 'recientes';
        switch ($ordenamiento) {
            case 'precio_asc':
                $query->orderBy('precio_base', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio_base', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            case 'recientes':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $productos = $query->paginate(12);

        // Obtener opciones para filtros
        $tiposImpresion = Producto::distinct()->pluck('tipo_impresion');
        $tiposPapel = Producto::distinct()->pluck('tipo_papel');
        $tamanosPapel = TamanoPapel::all();

        return view('Client.productos', compact(
            'productos', 
            'tiposImpresion', 
            'tiposPapel', 
            'tamanosPapel',
            'ordenamiento'
        ));
    }

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
