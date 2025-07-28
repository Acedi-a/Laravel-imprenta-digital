@extends('Layouts.LayoutAdmin')

@section('title', 'Detalle de Pedido')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded shadow p-8 mt-6">
    <h2 class="text-2xl font-bold mb-4">Detalle del Pedido #{{ $pedido->numero_pedido }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-semibold text-lg mb-2">Datos del Cliente</h3>
            <div><span class="font-medium">Nombre:</span> {{ $pedido->cotizacion->usuario->nombre }}</div>
            <div><span class="font-medium">Email:</span> {{ $pedido->cotizacion->usuario->email }}</div>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-2">Producto</h3>
            <div><span class="font-medium">Nombre:</span> {{ $pedido->cotizacion->producto->nombre }}</div>
            <div><span class="font-medium">Descripción:</span> {{ $pedido->cotizacion->producto->descripcion ?? '-' }}</div>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-2">Cotización</h3>
            <div><span class="font-medium">ID:</span> #{{ $pedido->cotizacion->id }}</div>
            <div><span class="font-medium">Cantidad:</span> {{ $pedido->cotizacion->cantidad }}</div>
            <div><span class="font-medium">Precio Total:</span> S/ {{ number_format($pedido->cotizacion->precio_total, 2) }}</div>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-2">Estado del Pedido</h3>
            <div><span class="font-medium">Estado:</span> <span class="inline-flex px-2 text-xs font-semibold rounded-full {{ $pedido->estado == 'cancelado' ? 'bg-red-100 text-red-800' : ($pedido->estado == 'finalizado' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">{{ ucfirst($pedido->estado) }}</span></div>
            <div><span class="font-medium">Prioridad:</span> {{ ucfirst($pedido->prioridad) }}</div>
            <div><span class="font-medium">Fecha:</span> {{ $pedido->fecha_pedido }}</div>
        </div>
    </div>

    <div class="border-t pt-6 mt-6">
        <h3 class="font-semibold text-lg mb-2">Pago</h3>
        @if($pago)
            <div class="mb-2"><span class="font-medium">Estado:</span> <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-green-100 text-green-800">Pagado</span></div>
            <div><span class="font-medium">Método:</span> {{ ucfirst($pago->metodo) }}</div>
            <div><span class="font-medium">Fecha de pago:</span> {{ $pago->created_at }}</div>
            <div><span class="font-medium">Referencia:</span> {{ $pago->referencia ?? '-' }}</div>
            <div class="mt-4">
                <span class="font-medium">Comprobante PDF:</span>
                @if($pdfUrl)
                    <iframe src="{{ $pdfUrl }}" class="w-full h-96 border rounded" title="Comprobante PDF"></iframe>
                    <a href="{{ $pdfUrl }}" target="_blank" class="text-blue-600 hover:underline block mt-2">Descargar PDF</a>
                @else
                    <span class="text-red-500 text-sm">No se ha generado el comprobante PDF.</span>
                @endif
            </div>
        @else
            <div class="mb-2"><span class="font-medium">Estado:</span> <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pendiente</span></div>
            <div class="mt-4 text-sm text-gray-500">Aún no se ha realizado el pago, por lo que no hay comprobante disponible.</div>
        @endif
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.pedidos.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Volver a la lista</a>
    </div>
</div>
@endsection
