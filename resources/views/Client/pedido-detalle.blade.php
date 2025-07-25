@extends('Layouts.LayoutCliente')

@section('title', 'Detalle de Pedido | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y botón de volver -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-indigo-800">Detalle de Pedido</h1>
            <p class="text-gray-600">Información completa de tu pedido #{{ $pedido->id }}</p>
        </div>
        <a href="{{ route('client.pedidos') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Volver a pedidos
        </a>
    </div>

    <!-- Barra de estado del pedido -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="mr-4">
                    @if($pedido->estado == 'pendiente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i> Pendiente
                        </span>
                    @elseif($pedido->estado == 'en_proceso')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-cog mr-1"></i> En proceso
                        </span>
                    @elseif($pedido->estado == 'impreso')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-print mr-1"></i> Impreso
                        </span>
                    @elseif($pedido->estado == 'en_camino')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            <i class="fas fa-truck mr-1"></i> En camino
                        </span>
                    @elseif($pedido->estado == 'entregado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Entregado
                        </span>
                    @elseif($pedido->estado == 'cancelado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> Cancelado
                        </span>
                    @endif
                </div>
                <div>
                    <h2 class="text-xl font-semibold">Pedido #{{ $pedido->id }}</h2>
                    <p class="text-gray-500 text-sm">Realizado el {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                @if($pedido->estado != 'cancelado' && $pedido->estado != 'entregado')
                    <a href="{{ route('client.pedido-seguimiento', $pedido->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                        <i class="fas fa-map-marker-alt mr-2"></i> Seguimiento
                    </a>
                @endif
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
                    <i class="fas fa-print mr-2"></i> Imprimir
                </button>
            </div>
        </div>

        <!-- Barra de progreso -->
        @if($pedido->estado != 'cancelado')
        <div class="relative">
            @php
                $estados = ['pendiente', 'en_proceso', 'impreso', 'en_camino', 'entregado'];
                $estadoActual = array_search($pedido->estado, $estados);
                $porcentaje = $estadoActual !== false ? ($estadoActual / (count($estados) - 1)) * 100 : 0;
            @endphp
            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                <div style="" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-500"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-600 mt-2">
                <div class="w-1/5 text-center {{ $estadoActual >= 0 ? 'font-medium text-indigo-600' : '' }}">
                    <div class="relative">
                        <div class="absolute left-1/2 -translate-x-1/2 -top-6 {{ $estadoActual >= 0 ? 'text-indigo-600' : 'text-gray-400' }}">
                            <i class="fas fa-circle {{ $estadoActual >= 0 ? 'text-indigo-600' : 'text-gray-300' }}"></i>
                        </div>
                    </div>
                    Pendiente
                </div>
                <div class="w-1/5 text-center {{ $estadoActual >= 1 ? 'font-medium text-indigo-600' : '' }}">
                    <div class="relative">
                        <div class="absolute left-1/2 -translate-x-1/2 -top-6 {{ $estadoActual >= 1 ? 'text-indigo-600' : 'text-gray-400' }}">
                            <i class="fas fa-circle {{ $estadoActual >= 1 ? 'text-indigo-600' : 'text-gray-300' }}"></i>
                        </div>
                    </div>
                    En proceso
                </div>
                <div class="w-1/5 text-center {{ $estadoActual >= 2 ? 'font-medium text-indigo-600' : '' }}">
                    <div class="relative">
                        <div class="absolute left-1/2 -translate-x-1/2 -top-6 {{ $estadoActual >= 2 ? 'text-indigo-600' : 'text-gray-400' }}">
                            <i class="fas fa-circle {{ $estadoActual >= 2 ? 'text-indigo-600' : 'text-gray-300' }}"></i>
                        </div>
                    </div>
                    Impreso
                </div>
                <div class="w-1/5 text-center {{ $estadoActual >= 3 ? 'font-medium text-indigo-600' : '' }}">
                    <div class="relative">
                        <div class="absolute left-1/2 -translate-x-1/2 -top-6 {{ $estadoActual >= 3 ? 'text-indigo-600' : 'text-gray-400' }}">
                            <i class="fas fa-circle {{ $estadoActual >= 3 ? 'text-indigo-600' : 'text-gray-300' }}"></i>
                        </div>
                    </div>
                    En camino
                </div>
                <div class="w-1/5 text-center {{ $estadoActual >= 4 ? 'font-medium text-indigo-600' : '' }}">
                    <div class="relative">
                        <div class="absolute left-1/2 -translate-x-1/2 -top-6 {{ $estadoActual >= 4 ? 'text-indigo-600' : 'text-gray-400' }}">
                            <i class="fas fa-circle {{ $estadoActual >= 4 ? 'text-indigo-600' : 'text-gray-300' }}"></i>
                        </div>
                    </div>
                    Entregado
                </div>
            </div>
        </div>
        @else
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Este pedido ha sido cancelado. Si necesitas más información, por favor contacta con nuestro servicio de atención al cliente.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <!-- Detalles del producto -->
        <div class="md:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">Detalles del producto</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Imagen del producto -->
                    <div class="md:w-1/3 mb-4 md:mb-0 md:pr-6">
                        @if($pedido->cotizacion->producto->imagen)
                            <img src="{{ asset('storage/' . $pedido->cotizacion->producto->imagen) }}" alt="{{ $pedido->cotizacion->producto->nombre }}" class="w-full h-auto rounded-lg object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Información del producto -->
                    <div class="md:w-2/3">
                        <h4 class="text-xl font-semibold mb-2">{{ $pedido->cotizacion->producto->nombre }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Tipo de impresión:</span> {{ $pedido->cotizacion->producto->tipo_impresion }}</p>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Tipo de papel:</span> {{ $pedido->cotizacion->producto->tipo_papel }}</p>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Tamaño:</span> {{ $pedido->cotizacion->producto->tamanoPapel->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Cantidad:</span> {{ $pedido->cotizacion->cantidad }} unidades</p>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Acabados:</span> {{ $pedido->cotizacion->acabados ?? 'Estándar' }}</p>
                                @if($pedido->fecha_entrega_estimada)
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Entrega estimada:</span> {{ $pedido->fecha_entrega_estimada->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="font-medium mb-2">Descripción:</h5>
                            <p class="text-gray-600">{{ $pedido->cotizacion->producto->descripcion }}</p>
                        </div>
                    </div>
                </div>

                <!-- Archivos adjuntos -->
                @if($pedido->cotizacion->archivos && count($pedido->cotizacion->archivos) > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium mb-3">Archivos adjuntos:</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pedido->cotizacion->archivos as $archivo)
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <div class="mr-3">
                                @if(in_array($archivo->tipo_mime, ['image/jpeg', 'image/png', 'image/gif']))
                                    <i class="fas fa-file-image text-blue-500 text-2xl"></i>
                                @elseif(in_array($archivo->tipo_mime, ['application/pdf']))
                                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                @elseif(in_array($archivo->tipo_mime, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                    <i class="fas fa-file-word text-indigo-500 text-2xl"></i>
                                @else
                                    <i class="fas fa-file text-gray-500 text-2xl"></i>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium truncate">{{ $archivo->nombre_original }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($archivo->tamaño_archivo / 1024, 2) }} KB</p>
                            </div>
                            <a href="{{ route('client.archivo-descargar', $archivo->id) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Comentarios adicionales -->
                @if($pedido->cotizacion->comentarios)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium mb-2">Comentarios adicionales:</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $pedido->cotizacion->comentarios }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Resumen de precios y pago -->
        <div class="space-y-8">
            <!-- Resumen de precios -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800">Resumen de precios</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Precio unitario:</span>
                            <span class="font-medium">${{ number_format($pedido->cotizacion->precio_unitario, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cantidad:</span>
                            <span class="font-medium">{{ $pedido->cotizacion->cantidad }} unidades</span>
                        </div>
                        @if($pedido->cotizacion->costo_adicional > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Costos adicionales:</span>
                            <span class="font-medium">${{ number_format($pedido->cotizacion->costo_adicional, 2) }}</span>
                        </div>
                        @endif
                        @if($pedido->cotizacion->descuento > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Descuento:</span>
                            <span class="font-medium">-${{ number_format($pedido->cotizacion->descuento, 2) }}</span>
                        </div>
                        @endif
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span>${{ number_format($pedido->cotizacion->precio_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de pago -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800">Información de pago</h3>
                </div>
                <div class="p-6">
                    @if($pedido->pagos && count($pedido->pagos) > 0)
                        @foreach($pedido->pagos as $pago)
                            <div class="mb-4 last:mb-0 p-3 {{ $pago->estado == 'completado' ? 'bg-green-50 border-l-4 border-green-400' : 'bg-yellow-50 border-l-4 border-yellow-400' }} rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium">Pago #{{ $pago->id }}</p>
                                        <p class="text-sm text-gray-600">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold">${{ number_format($pago->monto, 2) }}</p>
                                        <p class="text-sm {{ $pago->estado == 'completado' ? 'text-green-600' : 'text-yellow-600' }}">
                                            @if($pago->estado == 'completado')
                                                <i class="fas fa-check-circle mr-1"></i> Completado
                                            @elseif($pago->estado == 'pendiente')
                                                <i class="fas fa-clock mr-1"></i> Pendiente
                                            @elseif($pago->estado == 'fallido')
                                                <i class="fas fa-times-circle mr-1"></i> Fallido
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600"><span class="font-medium">Método:</span> {{ $pago->metodo_pago }}</p>
                                    @if($pago->referencia)
                                        <p class="text-sm text-gray-600"><span class="font-medium">Referencia:</span> {{ $pago->referencia }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($pedido->saldo_pendiente > 0)
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <p class="font-medium text-blue-800">Saldo pendiente:</p>
                                    <p class="font-bold text-blue-800">${{ number_format($pedido->saldo_pendiente, 2) }}</p>
                                </div>
                                <div class="mt-3">
                                    <a href="#" class="inline-block w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-center rounded-lg transition duration-200">
                                        <i class="fas fa-credit-card mr-1"></i> Completar pago
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="py-6 text-center">
                            <i class="fas fa-exclamation-circle text-4xl text-yellow-400 mb-3"></i>
                            <h4 class="text-lg font-medium text-gray-800 mb-2">No hay pagos registrados</h4>
                            <p class="text-gray-600 mb-4">No se han registrado pagos para este pedido.</p>
                            <a href="#" class="inline-block py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                                <i class="fas fa-credit-card mr-1"></i> Realizar pago
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800">¿Necesitas ayuda?</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <p class="text-gray-600">Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos:</p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-indigo-500 mr-3"></i>
                                <span class="text-gray-600">pedidos@imprentadigital.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-indigo-500 mr-3"></i>
                                <span class="text-gray-600">+591 3 555-1234</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-comment-alt text-indigo-500 mr-3"></i>
                                <a href="#" class="text-indigo-600 hover:text-indigo-800">Chat en línea</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial del pedido -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
            <h3 class="text-lg font-semibold text-indigo-800">Historial del pedido</h3>
        </div>
        <div class="p-6">
            <div class="relative">
                <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-200"></div>
                <ul class="space-y-6">
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-indigo-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Pedido realizado</h4>
                            <p class="text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-gray-600 mt-1">Has realizado un pedido basado en la cotización #{{ $pedido->cotizacion_id }}.</p>
                        </div>
                    </li>
                    
                    @if($pedido->estado != 'pendiente' && $pedido->estado != 'cancelado')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-blue-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-cog text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Pedido en proceso</h4>
                            <p class="text-sm text-gray-500">{{ $pedido->fecha_proceso ? $pedido->fecha_proceso->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Tu pedido ha sido confirmado y está siendo procesado por nuestro equipo.</p>
                        </div>
                    </li>
                    @endif
                    
                    @if($pedido->estado == 'impreso' || $pedido->estado == 'en_camino' || $pedido->estado == 'entregado')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-purple-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-print text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Impresión completada</h4>
                            <p class="text-sm text-gray-500">{{ $pedido->fecha_impresion ? $pedido->fecha_impresion->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Tu pedido ha sido impreso y está listo para ser enviado.</p>
                        </div>
                    </li>
                    @endif
                    
                    @if($pedido->estado == 'en_camino' || $pedido->estado == 'entregado')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-indigo-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-truck text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Pedido en camino</h4>
                            <p class="text-sm text-gray-500">{{ $pedido->fecha_envio ? $pedido->fecha_envio->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Tu pedido está en camino a la dirección de entrega.</p>
                            @if($pedido->numero_seguimiento)
                                <p class="text-gray-600 mt-1"><span class="font-medium">Número de seguimiento:</span> {{ $pedido->numero_seguimiento }}</p>
                            @endif
                        </div>
                    </li>
                    @endif
                    
                    @if($pedido->estado == 'entregado')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-green-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Pedido entregado</h4>
                            <p class="text-sm text-gray-500">{{ $pedido->fecha_entrega ? $pedido->fecha_entrega->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Tu pedido ha sido entregado con éxito. ¡Gracias por tu compra!</p>
                        </div>
                    </li>
                    @endif
                    
                    @if($pedido->estado == 'cancelado')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-red-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Pedido cancelado</h4>
                            <p class="text-sm text-gray-500">{{ $pedido->fecha_cancelacion ? $pedido->fecha_cancelacion->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Este pedido ha sido cancelado. Si tienes alguna pregunta, por favor contacta con nuestro servicio de atención al cliente.</p>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection