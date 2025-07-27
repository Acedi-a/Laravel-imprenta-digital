<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with(['producto', 'archivo'])
            ->where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Client.cotizaciones', compact('cotizaciones'));
    }

    public function mostrarFormulario()
    {
        // Aquí deberías obtener los datos necesarios para el formulario
        $productos = Producto::all(); // o como tengas configurado

        return view('Client.crear-cotizacion', compact('productos'));
        // Ajusta el nombre de la vista según tu estructura
    }

    public function detalle($id)
    {
        $cotizacion = Cotizacion::with(['producto', 'archivo'])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        return view('Client.cotizacion-detalle', compact('cotizacion'));
    }

    public function crear(Request $request)
    {
        if ($request->isMethod('get')) {
            // Mostrar formulario
            $productos = Producto::all();
            return view('Client.crear-cotizacion', compact('productos'));
        }

        if ($request->isMethod('post')) {
            // Procesar formulario
            // Tu lógica actual para crear la cotización
        }
    }

        public function cotizarDesdeProducto(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);

        $producto = Producto::findOrFail($id);

        // Guardar archivo
        $archivoSubido = $request->file('archivo');
        $nombreOriginal = $archivoSubido->getClientOriginalName();
        $ruta = $archivoSubido->store('cotizaciones', 'public');
        $archivo = Archivo::create([
            'usuario_id' => Auth::id(),
            'nombre_original' => $nombreOriginal,
            'ruta_guardado' => $ruta,
            'tamaño_archivo' => $archivoSubido->getSize() / 1024, // KB
            'tipo_mime' => $archivoSubido->getClientMimeType(),
        ]);

        $precioTotal = $producto->precio_base * $request->cantidad;

        $cotizacion = Cotizacion::create([
            'usuario_id' => Auth::id(),
            'producto_id' => $producto->id,
            'archivo_id' => $archivo->id,
            'cantidad' => $request->cantidad,
            'precio_total' => $precioTotal,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('client.cotizacion-detalle', $cotizacion->id)
            ->with('success', '¡Cotización creada correctamente!');
    }
}