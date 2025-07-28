<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 15px; }
        .ticket { max-width: 400px; margin: 0 auto; border: 1px dashed #333; padding: 24px; border-radius: 10px; }
        .titulo { font-size: 22px; font-weight: bold; color: #4f46e5; margin-bottom: 10px; }
        .dato { margin-bottom: 8px; }
        .uuid { font-size: 13px; color: #666; }
        .qr { text-align: center; margin: 18px 0; }
        .qr img { width: 120px; height: 120px; }
        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 18px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="titulo">Comprobante de Pago</div>
        <div class="dato"><strong>Pedido:</strong> #{{ $pedido->numero_pedido }}</div>
        <div class="dato"><strong>Cliente:</strong> {{ $pedido->cotizacion->usuario->nombre }} {{ $pedido->cotizacion->usuario->apellido }}</div>
        <div class="dato"><strong>Método de pago:</strong> {{ ucfirst($metodo) }}</div>
        <div class="dato"><strong>Fecha:</strong> {{ $fecha }}</div>
        <div class="dato"><strong>Total:</strong> ${{ number_format($pedido->cotizacion->precio_total, 2) }}</div>
        <div class="dato uuid"><strong>UUID Pedido:</strong> {{ $pedido->numero_pedido }}</div>
        @if($metodo === 'qr' && (!isset($showQr) || $showQr))
        <div class="qr">
            <img src="{{ isset($qrPath) ? $qrPath : '' }}" alt="QR Bancario">
            <div style="font-size:12px;color:#888;">* Demo QR</div>
        </div>
        @endif
        <div class="footer">
            Gracias por confiar en Imprenta Digital.<br>
            Este ticket es válido como comprobante de pago.<br>
            <span style="font-size:11px;">Generado: {{ $fecha }}</span>
        </div>
    </div>
</body>
</html>
