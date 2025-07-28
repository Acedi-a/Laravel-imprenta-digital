<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\PDF;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    // Muestra la página de pago para un pedido
    public function pagoPedido($id)
    {
        $pedido = Pedido::with('cotizacion')->whereHas('cotizacion', function($q) {
            $q->where('usuario_id', Auth::id());
        })->findOrFail($id);
        
        // Cargar direcciones del usuario
        $direcciones = \App\Models\Direccion::where('usuario_id', Auth::id())->get();
        
        return view('Client.pago-pedido', compact('pedido', 'direcciones'));
    }

    // Genera el comprobante PDF del pago, crea el pago y luego el pedido
    public function generarComprobante(Request $request)
    {
        $request->validate([
            'metodo' => 'required|string',
            'direccion_id' => 'required|exists:direcciones,id'
        ]);

        $pedidoId = $request->route('id');
        $pedido = \App\Models\Pedido::with('cotizacion.usuario', 'pago')->whereHas('cotizacion', function($q) {
            $q->where('usuario_id', Auth::id());
        })->findOrFail($pedidoId);
        
        // Verificar que la dirección pertenece al usuario autenticado
        $direccion = \App\Models\Direccion::where('id', $request->direccion_id)
                                         ->where('usuario_id', Auth::id())
                                         ->firstOrFail();
        
        $cotizacion = $pedido->cotizacion;
        $usuarioId = $cotizacion->usuario->id;
        $metodo = $request->input('metodo');
        $fecha = now()->format('d/m/Y H:i');

        if ($pedido->pago) {
            // Ya existe pago, solo descargar comprobante
            $pdfPath = $pedido->pago->comprobante_url;
            $filePath = storage_path('app/private/' . $pdfPath);
            if (!file_exists($filePath)) {
                return redirect()->route('client.pedidos')->with('error', 'No se encontró el comprobante.');
            }
            return response()->download($filePath, 'comprobante.pdf');
        }


        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $pdfDir = storage_path("app/private/pagos/{$usuarioId}");
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }
        $pdfPath = "pagos/{$usuarioId}/ticket-{$uuid}.pdf";
        $fullPdfPath = storage_path("app/private/" . $pdfPath);

        // Renderizar la vista sin imagen QR si no existe
        $qrPath = public_path('img/qr-demo.png');
        $showQr = file_exists($qrPath);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('Client.ticket-pago', [
            'pedido' => $pedido,
            'cotizacion' => $cotizacion,
            'metodo' => $metodo,
            'fecha' => $fecha,
            'showQr' => $showQr,
            'qrPath' => $qrPath,
        ]);
        file_put_contents($fullPdfPath, $pdf->output());

        // Crear el pago y asociarlo al pedido
        $pago = \App\Models\Pago::create([
            'monto' => $cotizacion->precio_total,
            'metodo' => $metodo,
            'fecha_pago' => now(),
            'estado' => 'completado',
            'referencia' => $uuid,
            'comprobante_url' => $pdfPath,
        ]);
        
        // Actualizar pedido con pago y dirección de envío
        $pedido->pago_id = $pago->id;
        $pedido->direccion_id = $request->direccion_id;
        $pedido->save();

        $filePath = storage_path('app/private/' . $pdfPath);
        if (!file_exists($filePath)) {
            return redirect()->route('client.pedidos')->with('error', 'No se pudo generar el comprobante.');
        }
        return response()->download($filePath, 'comprobante.pdf');
    }
}
