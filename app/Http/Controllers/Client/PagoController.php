                            <a href="{{ route('client.pago.pedido', $pedido->id) }}" class="inline-block py-3 px-6 bg-gradient-to-r from-green-500 to-indigo-600 hover:from-green-600 hover:to-indigo-700 text-white rounded-lg text-lg font-semibold shadow-lg transition duration-200">
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\PDF;

class PagoController extends Controller
{
    // Muestra la página de pago para un pedido
    public function pagoPedido($id)
    {
        $pedido = Pedido::with('cotizacion')->whereHas('cotizacion', function($q) {
            $q->where('usuario_id', Auth::id());
        })->findOrFail($id);
        return view('Client.pago-pedido', compact('pedido'));
    }

    // Genera el comprobante PDF del pago
    public function generarComprobante(Request $request, $id)
    {
        $pedido = Pedido::with('cotizacion')->whereHas('cotizacion', function($q) {
            $q->where('usuario_id', Auth::id());
        })->findOrFail($id);
        $metodo = $request->input('metodo');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('Client.ticket-pago', [
            'pedido' => $pedido,
            'metodo' => $metodo,
            'fecha' => now()->format('d/m/Y H:i'),
        ]);
        return $pdf->download('ticket-pago-' . $pedido->numero_pedido . '.pdf');
    }
}
