<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: sans-serif; }
        h1 { color: #4f46e5; }
        table { width:100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #e5e7eb; text-align: left; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>

    @if(isset($stats))
        <h2>Resumen Global</h2>
        <ul>
            <li>Productos: {{ $stats['productos'] }}</li>
            <li>Usuarios: {{ $stats['usuarios'] }}</li>
            <li>Cotizaciones: {{ $stats['cotizaciones'] }}</li>
            <li>Pedidos: {{ $stats['pedidos'] }}</li>
            <li>Pagos: {{ $stats['pagos'] }}</li>
            <li>Envíos: {{ $stats['envios'] }}</li>
            <li>Ingresos totales aprobados: ${{ number_format($ingresosTotales,2) }}</li>
        </ul>
    @else
        <table>
            <thead>
                <tr>
                @foreach($items->first()->toArray() as $k=>$v)
                    <th>{{ ucfirst($k) }}</th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                    @foreach($item->toArray() as $v)
                        <td>{{ is_array($v) ? json_encode($v) : $v }}</td>
                    @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>