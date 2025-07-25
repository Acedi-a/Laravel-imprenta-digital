@extends('Layouts.LayoutCliente')

@section('title', 'Seguimiento de Pedido | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y botón de volver -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-indigo-800">Seguimiento de Pedido</h1>
            <p class="text-gray-600">Rastrea el estado actual de tu pedido #{{ $pedido->id }}</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <a href="{{ route('client.pedido-detalle', $pedido->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
                <i class="fas fa-info-circle mr-2"></i> Ver detalles
            </a>
            <a href="{{ route('client.pedidos') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Volver a pedidos
            </a>
        </div>
    </div>

    <!-- Información del pedido -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
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
            <div>
                @if($pedido->fecha_entrega_estimada && $pedido->estado != 'entregado' && $pedido->estado != 'cancelado')
                <div class="text-right">
                    <p class="text-sm text-gray-600">Entrega estimada:</p>
                    <p class="font-semibold text-indigo-800">{{ $pedido->fecha_entrega_estimada->format('d/m/Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mapa de seguimiento -->
    @if($pedido->estado == 'en_camino')
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
            <h3 class="text-lg font-semibold text-indigo-800">Ubicación en tiempo real</h3>
        </div>
        <div class="p-6">
            <div class="aspect-w-16 aspect-h-9 mb-4">
                <div id="map" class="w-full h-96 rounded-lg border border-gray-200"></div>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600"><span class="font-medium">Última actualización:</span> <span id="last-update">{{ now()->format('d/m/Y H:i:s') }}</span></p>
                </div>
                <button id="refresh-map" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-lg hover:bg-indigo-200 transition duration-200">
                    <i class="fas fa-sync-alt mr-1"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Línea de tiempo del pedido -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <div class="md:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">Línea de tiempo</h3>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-200"></div>
                    <ul class="space-y-8">
                        <li class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-8 w-8 rounded-full {{ $pedido->created_at ? 'bg-indigo-100 border-4 border-white shadow' : 'bg-gray-100 border-4 border-white shadow' }} flex items-center justify-center">
                                <i class="fas fa-shopping-cart {{ $pedido->created_at ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">Pedido realizado</h4>
                                <p class="text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-gray-600 mt-1">Tu pedido ha sido recibido y está siendo procesado.</p>
                            </div>
                        </li>

                        <li class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-8 w-8 rounded-full {{ $pedido->estado != 'pendiente' ? 'bg-blue-100 border-4 border-white shadow' : 'bg-gray-100 border-4 border-white shadow' }} flex items-center justify-center">
                                <i class="fas fa-cog {{ $pedido->estado != 'pendiente' ? 'text-blue-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">En proceso</h4>
                                <p class="text-sm text-gray-500">{{ $pedido->fecha_proceso ? $pedido->fecha_proceso->format('d/m/Y H:i') : 'Pendiente' }}</p>
                                <p class="text-gray-600 mt-1">{{ $pedido->estado != 'pendiente' ? 'Tu pedido está siendo preparado por nuestro equipo.' : 'Tu pedido entrará en producción pronto.' }}</p>
                            </div>
                        </li>

                        <li class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-8 w-8 rounded-full {{ $pedido->estado == 'impreso' || $pedido->estado == 'en_camino' || $pedido->estado == 'entregado' ? 'bg-purple-100 border-4 border-white shadow' : 'bg-gray-100 border-4 border-white shadow' }} flex items-center justify-center">
                                <i class="fas fa-print {{ $pedido->estado == 'impreso' || $pedido->estado == 'en_camino' || $pedido->estado == 'entregado' ? 'text-purple-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">Impresión completada</h4>
                                <p class="text-sm text-gray-500">{{ $pedido->fecha_impresion ? $pedido->fecha_impresion->format('d/m/Y H:i') : 'Pendiente' }}</p>
                                <p class="text-gray-600 mt-1">{{ $pedido->estado == 'impreso' || $pedido->estado == 'en_camino' || $pedido->estado == 'entregado' ? 'Tu pedido ha sido impreso y está siendo preparado para envío.' : 'Tu pedido será impreso según las especificaciones.' }}</p>
                            </div>
                        </li>

                        <li class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-8 w-8 rounded-full {{ $pedido->estado == 'en_camino' || $pedido->estado == 'entregado' ? 'bg-indigo-100 border-4 border-white shadow' : 'bg-gray-100 border-4 border-white shadow' }} flex items-center justify-center">
                                <i class="fas fa-truck {{ $pedido->estado == 'en_camino' || $pedido->estado == 'entregado' ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">En camino</h4>
                                <p class="text-sm text-gray-500">{{ $pedido->fecha_envio ? $pedido->fecha_envio->format('d/m/Y H:i') : 'Pendiente' }}</p>
                                <p class="text-gray-600 mt-1">{{ $pedido->estado == 'en_camino' || $pedido->estado == 'entregado' ? 'Tu pedido está en camino a la dirección de entrega.' : 'Tu pedido será enviado una vez completada la impresión.' }}</p>
                                @if($pedido->numero_seguimiento && ($pedido->estado == 'en_camino' || $pedido->estado == 'entregado'))
                                <p class="text-gray-600 mt-1"><span class="font-medium">Número de seguimiento:</span> {{ $pedido->numero_seguimiento }}</p>
                                @endif
                            </div>
                        </li>

                        <li class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-8 w-8 rounded-full {{ $pedido->estado == 'entregado' ? 'bg-green-100 border-4 border-white shadow' : 'bg-gray-100 border-4 border-white shadow' }} flex items-center justify-center">
                                <i class="fas fa-check {{ $pedido->estado == 'entregado' ? 'text-green-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">Entregado</h4>
                                <p class="text-sm text-gray-500">{{ $pedido->fecha_entrega ? $pedido->fecha_entrega->format('d/m/Y H:i') : 'Pendiente' }}</p>
                                <p class="text-gray-600 mt-1">{{ $pedido->estado == 'entregado' ? '¡Tu pedido ha sido entregado con éxito!' : 'Tu pedido será entregado en la dirección especificada.' }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="space-y-8">
            <!-- Detalles de envío -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800">Detalles de envío</h3>
                </div>
                <div class="p-6">
                    @if($pedido->envio)
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium mb-2">Dirección de entrega:</h4>
                            <p class="text-gray-600">{{ $pedido->envio->direccion }}</p>
                            <p class="text-gray-600">{{ $pedido->envio->ciudad }}, {{ $pedido->envio->estado }} {{ $pedido->envio->codigo_postal }}</p>
                            <p class="text-gray-600">{{ $pedido->envio->pais }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">Método de envío:</h4>
                            <p class="text-gray-600">{{ $pedido->envio->metodo_envio }}</p>
                        </div>

                        @if($pedido->numero_seguimiento)
                        <div>
                            <h4 class="font-medium mb-2">Número de seguimiento:</h4>
                            <div class="flex items-center">
                                <span class="text-gray-600 mr-2">{{ $pedido->numero_seguimiento }}</span>
                                <button class="text-indigo-600 hover:text-indigo-800" onclick="copyToClipboard('{{ $pedido->numero_seguimiento }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($pedido->envio->instrucciones_especiales)
                        <div>
                            <h4 class="font-medium mb-2">Instrucciones especiales:</h4>
                            <p class="text-gray-600">{{ $pedido->envio->instrucciones_especiales }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="py-4 text-center">
                        <p class="text-gray-600">No hay información de envío disponible.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contacto de entrega -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800">Contacto de entrega</h3>
                </div>
                <div class="p-6">
                    @if($pedido->envio && $pedido->envio->contacto_nombre)
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-user text-indigo-500 mr-3"></i>
                            <span class="text-gray-600">{{ $pedido->envio->contacto_nombre }}</span>
                        </div>
                        @if($pedido->envio->contacto_telefono)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-indigo-500 mr-3"></i>
                            <span class="text-gray-600">{{ $pedido->envio->contacto_telefono }}</span>
                        </div>
                        @endif
                        @if($pedido->envio->contacto_email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-indigo-500 mr-3"></i>
                            <span class="text-gray-600">{{ $pedido->envio->contacto_email }}</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="py-4 text-center">
                        <p class="text-gray-600">No hay información de contacto disponible.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ayuda -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800">¿Necesitas ayuda?</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <p class="text-gray-600">Si tienes alguna pregunta sobre tu envío, no dudes en contactarnos:</p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-indigo-500 mr-3"></i>
                                <span class="text-gray-600">envios@imprentadigital.com</span>
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
</div>

<!-- Notificación de copiado -->
<div id="copy-notification" class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg transform translate-y-10 opacity-0 transition-all duration-300">
    Número de seguimiento copiado al portapapeles
</div>
@endsection

@push('scripts')
@if($pedido->estado == 'en_camino')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const notification = document.getElementById('copy-notification');
            notification.classList.remove('translate-y-10', 'opacity-0');
            notification.classList.add('translate-y-0', 'opacity-100');

            setTimeout(function() {
                notification.classList.remove('translate-y-0', 'opacity-100');
                notification.classList.add('translate-y-10', 'opacity-0');
            }, 2000);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const lat = -17.783181;
        const lng = -63.182126;

        const map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup("<b>Tu pedido está aquí</b><br>En camino a tu dirección.").openPopup();

        document.getElementById('refresh-map').addEventListener('click', function() {
            const newLat = lat + (Math.random() - 0.5) * 0.01;
            const newLng = lng + (Math.random() - 0.5) * 0.01;
            marker.setLatLng([newLat, newLng]);
            map.panTo([newLat, newLng]);
            document.getElementById('last-update').textContent = new Date().toLocaleString();
        });
    });
</script>
@endif
@endpush