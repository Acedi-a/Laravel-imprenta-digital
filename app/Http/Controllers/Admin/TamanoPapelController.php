<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TamanoPapel;
use Illuminate\Http\Request;

class TamanoPapelController extends Controller
{
    public function index()
    {
        $tamanos = TamanoPapel::orderBy('id', 'asc')->get();
        return view('Admin.TamanoPapel.index', compact('tamanos'));
    }

    public function store(Request $request)
    {
        TamanoPapel::create($request->all());
        return back()->with('success', 'Tamaño de papel creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $tamano = TamanoPapel::findOrFail($id);
        $tamano->update($request->all());
        return back()->with('success', 'Tamaño de papel actualizado');
    }

    public function destroy($id)
    {
        $tamano = TamanoPapel::findOrFail($id);
        $tamano->delete();
        return back()->with('success', 'Tamaño de papel eliminado');
    }
}
