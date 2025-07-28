@extends('Layouts.LayoutCliente')

@section('title', 'Seguimiento de Envío | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-indigo-800">Seguimiento de Envío</h1>
            <p class="text-gray-600">Pedido #{{ $pedido->id }}</p>
        </div>
        <a href="{{ route('client.pedidos') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Volver a pedidos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        @if($envio)
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Datos del Envío</h2>
                <p><strong>Transportista:</strong> {{ $envio->transportista }}</p>
                <p><strong>Código de seguimiento:</strong> {{ $envio->codigo_seguimiento }}</p>
                <p><strong>Fecha de envío:</strong> {{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : '-' }}</p>
                <p><strong>Fecha estimada de entrega:</strong> {{ $envio->fecha_estimada_entrega ? $envio->fecha_estimada_entrega->format('d/m/Y H:i') : '-' }}</p>
            </div>
            <div class="mb-4">
                <h2 class="text-lg font-semibold mb-2">Dirección de entrega</h2>
                @if($envio->direccion)
                    <p>{{ $envio->direccion->direccion }}</p>
                @else
                    <p class="text-gray-500">No disponible</p>
                @endif
            </div>
        @else
            <div class="text-gray-500">Aún no se ha generado el envío para este pedido.</div>
        @endif
    </div>
</div>
@endsection
