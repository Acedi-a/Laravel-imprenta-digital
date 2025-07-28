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
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 text-indigo-700">Datos del Envío</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Transportista</p>
                        <p class="text-lg">{{ $envio->transportista ?: 'No asignado' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Código de seguimiento</p>
                        <p class="text-lg font-mono bg-gray-100 px-2 py-1 rounded">ENV-{{ str_pad($envio->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha de envío</p>
                        <p class="text-lg">{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : 'Pendiente' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha estimada de entrega</p>
                        <p class="text-lg">{{ $envio->fecha_estimada_entrega ? $envio->fecha_estimada_entrega->format('d/m/Y H:i') : 'Por definir' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3 text-indigo-700">Dirección de entrega</h3>
                @if($pedido->direccion)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="font-medium">{{ $pedido->direccion->nombre_direccion }}</p>
                        <p class="text-gray-600">{{ $pedido->direccion->direccion }}</p>
                        @if($pedido->direccion->ciudad)
                            <p class="text-gray-600">{{ $pedido->direccion->ciudad }}</p>
                        @endif
                        @if($pedido->direccion->codigo_postal)
                            <p class="text-gray-600">CP: {{ $pedido->direccion->codigo_postal }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 italic">No se ha especificado dirección de entrega</p>
                @endif
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-shipping-fast text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aún no se ha generado el envío para este pedido.</p>
                <p class="text-gray-400 text-sm">Se creará automáticamente cuando el pedido esté finalizado.</p>
            </div>
        @endif
    </div>
</div>
@endsection
