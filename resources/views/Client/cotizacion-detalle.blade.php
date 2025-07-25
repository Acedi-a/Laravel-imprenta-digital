@extends('Layouts.LayoutCliente')

@section('title', 'Detalle de Cotización | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y botón de volver -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-indigo-800">Detalle de Cotización</h1>
            <p class="text-gray-600">Información completa de tu solicitud de cotización.</p>
        </div>
        <a href="{{ route('client.cotizaciones') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Volver a cotizaciones
        </a>
    </div>

    <!-- Información de estado y acciones -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="mr-4">
                    @if($cotizacion->estado == 'pendiente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i> Pendiente
                        </span>
                    @elseif($cotizacion->estado == 'aprobada')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Aprobada
                        </span>
                    @elseif($cotizacion->estado == 'rechazada')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> Rechazada
                        </span>
                    @elseif($cotizacion->estado == 'expirada')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-calendar-times mr-1"></i> Expirada
                        </span>
                    @endif
                </div>
                <div>
                    <h2 class="text-xl font-semibold">Cotización #{{ $cotizacion->id }}</h2>
                    <p class="text-gray-500 text-sm">Creada el {{ $cotizacion->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                @if($cotizacion->estado == 'aprobada')
                    <button onclick="confirmarPedido('{{ $cotizacion->id }}')" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i> Realizar pedido
                    </button>
                @endif
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
                    <i class="fas fa-print mr-2"></i> Imprimir
                </button>
            </div>
        </div>
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
                        @if($cotizacion->producto->imagen)
                            <img src="{{ asset('storage/' . $cotizacion->producto->imagen) }}" alt="{{ $cotizacion->producto->nombre }}" class="w-full h-auto rounded-lg object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Información del producto -->
                    <div class="md:w-2/3">
                        <h4 class="text-xl font-semibold mb-2">{{ $cotizacion->producto->nombre }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Tipo de impresión:</span> {{ $cotizacion->producto->tipo_impresion }}</p>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Tipo de papel:</span> {{ $cotizacion->producto->tipo_papel }}</p>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Tamaño:</span> {{ $cotizacion->producto->tamanoPapel->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Cantidad:</span> {{ $cotizacion->cantidad }} unidades</p>
                                <p class="text-gray-600 mb-1"><span class="font-medium">Acabados:</span> {{ $cotizacion->acabados ?? 'Estándar' }}</p>
                                @if($cotizacion->tiempo_entrega)
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Tiempo estimado:</span> {{ $cotizacion->tiempo_entrega }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="font-medium mb-2">Descripción:</h5>
                            <p class="text-gray-600">{{ $cotizacion->producto->descripcion }}</p>
                        </div>
                    </div>
                </div>

                <!-- Archivos adjuntos -->
                @if($cotizacion->archivos && count($cotizacion->archivos) > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium mb-3">Archivos adjuntos:</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($cotizacion->archivos as $archivo)
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
                @if($cotizacion->comentarios)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium mb-2">Comentarios adicionales:</h5>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $cotizacion->comentarios }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Resumen de precios -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">Resumen de precios</h3>
            </div>
            <div class="p-6">
                @if($cotizacion->precio_total)
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Precio unitario:</span>
                            <span class="font-medium">${{ number_format($cotizacion->precio_unitario, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cantidad:</span>
                            <span class="font-medium">{{ $cotizacion->cantidad }} unidades</span>
                        </div>
                        @if($cotizacion->costo_adicional > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Costos adicionales:</span>
                            <span class="font-medium">${{ number_format($cotizacion->costo_adicional, 2) }}</span>
                        </div>
                        @endif
                        @if($cotizacion->descuento > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Descuento:</span>
                            <span class="font-medium">-${{ number_format($cotizacion->descuento, 2) }}</span>
                        </div>
                        @endif
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span>${{ number_format($cotizacion->precio_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-sm text-gray-600">Esta cotización es válida por 15 días a partir de la fecha de emisión. Los precios incluyen IVA.</p>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <i class="fas fa-hourglass-half text-4xl text-indigo-300 mb-3"></i>
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Cotización en proceso</h4>
                        <p class="text-gray-600">Nuestro equipo está calculando el precio para tu solicitud. Te notificaremos cuando esté lista.</p>
                    </div>
                @endif

                <!-- Información de contacto -->
                <div class="mt-4">
                    <h5 class="font-medium mb-3">¿Tienes preguntas?</h5>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-indigo-500 mr-3"></i>
                            <span class="text-gray-600">ventas@imprentadigital.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-indigo-500 mr-3"></i>
                            <span class="text-gray-600">+591 3 555-1234</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-indigo-500 mr-3"></i>
                            <span class="text-gray-600">Lun-Vie: 8:00 - 18:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de la cotización -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
            <h3 class="text-lg font-semibold text-indigo-800">Historial de la cotización</h3>
        </div>
        <div class="p-6">
            <div class="relative">
                <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-200"></div>
                <ul class="space-y-6">
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-indigo-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-plus-circle text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Cotización creada</h4>
                            <p class="text-sm text-gray-500">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-gray-600 mt-1">Has solicitado una cotización para {{ $cotizacion->cantidad }} unidades de {{ $cotizacion->producto->nombre }}.</p>
                        </div>
                    </li>
                    
                    @if($cotizacion->updated_at->gt($cotizacion->created_at))
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-blue-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-sync-alt text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Cotización actualizada</h4>
                            <p class="text-sm text-gray-500">{{ $cotizacion->updated_at->format('d/m/Y H:i') }}</p>
                            <p class="text-gray-600 mt-1">La cotización ha sido actualizada con nueva información.</p>
                        </div>
                    </li>
                    @endif
                    
                    @if($cotizacion->estado == 'aprobada')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-green-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Cotización aprobada</h4>
                            <p class="text-sm text-gray-500">{{ $cotizacion->fecha_aprobacion ? $cotizacion->fecha_aprobacion->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Tu cotización ha sido aprobada y está lista para proceder con el pedido.</p>
                        </div>
                    </li>
                    @elseif($cotizacion->estado == 'rechazada')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-red-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Cotización rechazada</h4>
                            <p class="text-sm text-gray-500">{{ $cotizacion->fecha_rechazo ? $cotizacion->fecha_rechazo->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">La cotización ha sido rechazada. Por favor, contacta con nuestro equipo para más información.</p>
                        </div>
                    </li>
                    @elseif($cotizacion->estado == 'expirada')
                    <li class="relative pl-10">
                        <div class="absolute left-0 top-1.5 h-7 w-7 rounded-full bg-gray-100 border-4 border-white shadow flex items-center justify-center">
                            <i class="fas fa-calendar-times text-gray-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">Cotización expirada</h4>
                            <p class="text-sm text-gray-500">{{ $cotizacion->fecha_expiracion ? $cotizacion->fecha_expiracion->format('d/m/Y H:i') : 'Fecha no disponible' }}</p>
                            <p class="text-gray-600 mt-1">Esta cotización ha expirado. Puedes solicitar una nueva cotización si aún estás interesado.</p>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para realizar pedido -->
<div id="pedido-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="text-center mb-4">
            <i class="fas fa-shopping-cart text-4xl text-indigo-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmar pedido</h3>
            <p class="text-gray-600">¿Estás seguro de que deseas realizar un pedido basado en esta cotización?</p>
        </div>
        <div class="flex justify-end space-x-3">
            <button id="cancel-pedido" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                Cancelar
            </button>
            <form id="pedido-form" action="" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-check mr-1"></i> Confirmar pedido
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar modal de confirmación para realizar pedido
    function confirmarPedido(id) {
        document.getElementById('pedido-form').action = `{{ route('client.pedido-crear', '') }}/${id}`;
        document.getElementById('pedido-modal').classList.remove('hidden');
    }

    // Cancelar pedido
    document.getElementById('cancel-pedido').addEventListener('click', function() {
        document.getElementById('pedido-modal').classList.add('hidden');
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('pedido-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endpush