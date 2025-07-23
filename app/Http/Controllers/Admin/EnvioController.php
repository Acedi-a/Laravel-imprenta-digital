<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use App\Models\Pedido;
use App\Models\Direccion;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function index()
    {
        $envios     = Envio::with(['pedido','direccion'])->orderBy('id','desc')->paginate(20);
        $pedidos    = Pedido::where('estado','!=','cancelado')->get();
        $direcciones = Direccion::all();
        return view('Admin.Envios.index', compact('envios','pedidos','direcciones'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'direccion_id'=> 'required|exists:direcciones,id',
            'transportista'=> 'required|string|max:100',
            'codigo_seguimiento' => 'required|string|max:100',
            'fecha_envio' => 'required|date',
            'fecha_estimada_entrega' => 'required|date|after:fecha_envio',
        ]);

        Envio::create($request->all());
        return redirect()->route('admin.envios.index')->with('success','Envío guardado.');
    }

    public function actualizar(Request $request, Envio $envio)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'direccion_id'=> 'required|exists:direcciones,id',
            'transportista'=> 'required|string|max:100',
            'codigo_seguimiento' => 'required|string|max:100',
            'fecha_envio' => 'required|date',
            'fecha_estimada_entrega' => 'required|date|after:fecha_envio',
        ]);

        $envio->update($request->all());
        return redirect()->route('admin.envios.index')->with('success','Envío actualizado.');
    }

    public function eliminar(Envio $envio)
    {
        $nuevoEstado = $envio->estado === 'cancelado' ? 'activo' : 'cancelado';
        $envio->update(['estado' => $nuevoEstado]);
        return back()->with('success','Estado del envío cambiado.');
    }
}