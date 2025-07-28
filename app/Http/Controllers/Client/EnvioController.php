<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Envio;
use Illuminate\Support\Facades\Auth;

class EnvioController extends Controller
{
    public function seguimiento($id)
    {
        $pedido = Pedido::with(['cotizacion.producto', 'envio.direccion'])
            ->whereHas('cotizacion', function($query) {
                $query->where('usuario_id', Auth::id());
            })
            ->findOrFail($id);
        $envio = $pedido->envio;
        return view('Client.envio-seguimiento', compact('pedido', 'envio'));
    }
}
