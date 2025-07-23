<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos   = Pago::with('pedido')->orderBy('id','desc')->paginate(20);
        $pedidos = Pedido::where('estado','!=','cancelado')->get();
        return view('Admin.Pagos.index', compact('pagos','pedidos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'monto'       => 'required|numeric|min:0',
            'metodo'      => 'required|string|max:50',
            'fecha_pago'  => 'required|date',
            'estado'      => 'required|in:pendiente,aprobado,rechazado,cancelado',
            'referencia'  => 'required|string|max:36|unique:pagos,referencia',
        ]);

        Pago::create($request->all());
        return redirect()->route('admin.pagos.index')->with('success','Pago guardado.');
    }

    public function actualizar(Request $request, Pago $pago)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'monto'       => 'required|numeric|min:0',
            'metodo'      => 'required|string|max:50',
            'fecha_pago'  => 'required|date',
            'estado'      => 'required|in:pendiente,aprobado,rechazado,cancelado',
            'referencia'  => 'required|string|max:36|unique:pagos,referencia,'.$pago->id,
        ]);

        $pago->update($request->all());
        return redirect()->route('admin.pagos.index')->with('success','Pago actualizado.');
    }

    public function eliminar(Pago $pago)
    {
        $nuevoEstado = $pago->estado === 'cancelado' ? 'pendiente' : 'cancelado';
        $pago->update(['estado' => $nuevoEstado]);
        return back()->with('success','Estado del pago cambiado.');
    }
}