<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    public function index()
    {
        $direcciones = Direccion::where('usuario_id', Auth::id())->get();
        return view('Client.direcciones', compact('direcciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'linea1' => 'required|string|max:255',
            'linea2' => 'nullable|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:20',
            'pais' => 'required|string|max:100',
        ]);

        // Si se marca como defecto, desmarcar las otras direcciones
        if ($request->has('defecto')) {
            Direccion::where('usuario_id', Auth::id())
                     ->update(['defecto' => false]);
        }

        $direccion = Direccion::create([
            'usuario_id' => Auth::id(),
            'linea1' => $request->linea1,
            'linea2' => $request->linea2,
            'ciudad' => $request->ciudad,
            'codigo_postal' => $request->codigo_postal,
            'pais' => $request->pais,
            'defecto' => $request->has('defecto') ? true : false,
        ]);

        return redirect()->route('client.direcciones.index')->with('success', 'Dirección guardada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $direccion = Direccion::where('usuario_id', Auth::id())->findOrFail($id);

        $request->validate([
            'linea1' => 'required|string|max:255',
            'linea2' => 'nullable|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:20',
            'pais' => 'required|string|max:100',
        ]);

        // Si se marca como defecto, desmarcar las otras direcciones
        if ($request->has('defecto')) {
            Direccion::where('usuario_id', Auth::id())
                     ->where('id', '!=', $id)
                     ->update(['defecto' => false]);
        }

        $direccion->update([
            'linea1' => $request->linea1,
            'linea2' => $request->linea2,
            'ciudad' => $request->ciudad,
            'codigo_postal' => $request->codigo_postal,
            'pais' => $request->pais,
            'defecto' => $request->has('defecto') ? true : false,
        ]);

        return redirect()->route('client.direcciones.index')->with('success', 'Dirección actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $direccion = Direccion::where('usuario_id', Auth::id())->findOrFail($id);
        $direccion->delete();

        return redirect()->route('client.direcciones.index')->with('success', 'Dirección eliminada exitosamente.');
    }
}
