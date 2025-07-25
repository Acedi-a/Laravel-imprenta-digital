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
}