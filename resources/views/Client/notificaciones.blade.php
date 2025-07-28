@extends('Layouts.LayoutCliente')

@section('title', 'Notificaciones | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y descripción -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2 text-indigo-800">Notificaciones</h1>
        <p class="text-gray-600">Mantente al día con actualizaciones sobre tus pedidos, cotizaciones y más.</p>
    </div>

    <!-- Estadísticas y acciones -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="bg-white rounded-xl shadow-md p-6 w-full md:w-auto">
            <div class="flex items-center space-x-8">
                <div class="flex items-center">
                    <div class="rounded-full bg-indigo-100 p-3 mr-3">
                        <i class="fas fa-bell text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $notificaciones->total() }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="rounded-full bg-red-100 p-3 mr-3">
                        <i class="fas fa-bell text-xl text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No leídas</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $no_leidas }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($no_leidas > 0)
        <form action="{{ route('client.notificaciones-todas-leidas') }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
                <i class="fas fa-check-double mr-2"></i> Marcar todas como leídas
            </button>
        </form>
        @endif
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form action="{{ route('client.notificaciones') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar en notificaciones</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Buscar por título o mensaje..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div class="w-full md:w-48">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="tipo" id="tipo" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    <option value="cotizacion" {{ request('tipo') == 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                    <option value="pedido" {{ request('tipo') == 'pedido' ? 'selected' : '' }}>Pedido</option>
                    <option value="pago" {{ request('tipo') == 'pago' ? 'selected' : '' }}>Pago</option>
                    <option value="envio" {{ request('tipo') == 'envio' ? 'selected' : '' }}>Envío</option>
                    <option value="sistema" {{ request('tipo') == 'sistema' ? 'selected' : '' }}>Sistema</option>
                </select>
            </div>

            <div class="w-full md:w-48">
                <label for="leido" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="leido" id="leido" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    <option value="0" {{ request('leido') === '0' ? 'selected' : '' }}>No leídas</option>
                    <option value="1" {{ request('leido') === '1' ? 'selected' : '' }}>Leídas</option>
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="flex space-x-2">
                <a href="{{ route('client.notificaciones') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-undo mr-1"></i> Limpiar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de notificaciones -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Centro de notificaciones</h2>
        </div>

        @if($notificaciones->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($notificaciones as $notificacion)
            <div class="p-6 hover:bg-gray-50 transition duration-150 {{ $notificacion->leido ? 'bg-white' : 'bg-indigo-50' }}">
                <div class="flex items-start">
                    <!-- Icono según tipo de notificación -->
                    <div class="flex-shrink-0 mr-4">
                        @if($notificacion->tipo == 'cotizacion')
                        <div class="rounded-full bg-blue-100 p-3">
                            <i class="fas fa-file-invoice-dollar text-xl text-blue-600"></i>
                        </div>
                        @elseif($notificacion->tipo == 'pedido')
                        <div class="rounded-full bg-green-100 p-3">
                            <i class="fas fa-shopping-cart text-xl text-green-600"></i>
                        </div>
                        @elseif($notificacion->tipo == 'pago')
                        <div class="rounded-full bg-yellow-100 p-3">
                            <i class="fas fa-credit-card text-xl text-yellow-600"></i>
                        </div>
                        @elseif($notificacion->tipo == 'envio')
                        <div class="rounded-full bg-indigo-100 p-3">
                            <i class="fas fa-truck text-xl text-indigo-600"></i>
                        </div>
                        @else
                        <div class="rounded-full bg-purple-100 p-3">
                            <i class="fas fa-bell text-xl text-purple-600"></i>
                        </div>
                        @endif
                    </div>

                    <!-- Contenido de la notificación -->
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $notificacion->titulo }}</h3>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-500">{{ $notificacion->created_at->diffForHumans() }}</span>

                                <!-- Indicador de no leído -->
                                @if(!$notificacion->leido)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">Nueva</span>
                                @endif
                            </div>
                        </div>

                        <p class="text-gray-600 mb-3">{{ $notificacion->mensaje }}</p>

                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <!-- Etiqueta de tipo -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($notificacion->tipo == 'cotizacion') bg-blue-100 text-blue-800
                                            @elseif($notificacion->tipo == 'pedido') bg-green-100 text-green-800
                                            @elseif($notificacion->tipo == 'pago') bg-yellow-100 text-yellow-800
                                            @elseif($notificacion->tipo == 'envio') bg-indigo-100 text-indigo-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                    {{ ucfirst($notificacion->tipo) }}
                                </span>
                            </div>

                            <div class="flex space-x-2">
                                @if(!$notificacion->leido)
                                <form action="{{ route('client.notificacion-leida', $notificacion->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        <i class="fas fa-check mr-1"></i> Marcar como leída
                                    </button>
                                </form>
                                @endif

                                <button onclick="confirmarEliminacion('{{ $notificacion->id }}')" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notificaciones->withQueryString()->links() }}
        </div>
        @else
        <div class="p-6 text-center">
            <i class="fas fa-bell-slash text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes notificaciones</h3>
            <p class="text-gray-500">Cuando recibas actualizaciones sobre tus pedidos o cotizaciones, aparecerán aquí.</p>
        </div>
        @endif
    </div>

    <!-- preferencias de notificaciones -->
    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Preferencias de notificaciones</h2>
        </div>
        <div class="p-6">
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notificaciones por correo electrónico</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_cotizaciones" name="email_cotizaciones" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_cotizaciones" class="font-medium text-gray-700">Actualizaciones de cotizaciones</label>
                                    <p class="text-gray-500">Recibe notificaciones cuando el estado de tus cotizaciones cambie.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_pedidos" name="email_pedidos" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_pedidos" class="font-medium text-gray-700">Actualizaciones de pedidos</label>
                                    <p class="text-gray-500">Recibe notificaciones sobre cambios en el estado de tus pedidos.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_pagos" name="email_pagos" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_pagos" class="font-medium text-gray-700">Confirmaciones de pago</label>
                                    <p class="text-gray-500">Recibe confirmaciones cuando se procesen tus pagos.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_promociones" name="email_promociones" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_promociones" class="font-medium text-gray-700">Promociones y ofertas</label>
                                    <p class="text-gray-500">Recibe información sobre promociones especiales y descuentos.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notificaciones en la plataforma</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="platform_cotizaciones" name="platform_cotizaciones" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="platform_cotizaciones" class="font-medium text-gray-700">Actualizaciones de cotizaciones</label>
                                    <p class="text-gray-500">Recibe notificaciones en la plataforma sobre tus cotizaciones.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="platform_pedidos" name="platform_pedidos" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="platform_pedidos" class="font-medium text-gray-700">Actualizaciones de pedidos</label>
                                    <p class="text-gray-500">Recibe notificaciones en la plataforma sobre tus pedidos.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="platform_pagos" name="platform_pagos" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="platform_pagos" class="font-medium text-gray-700">Confirmaciones de pago</label>
                                    <p class="text-gray-500">Recibe notificaciones en la plataforma sobre tus pagos.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="platform_sistema" name="platform_sistema" type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="platform_sistema" class="font-medium text-gray-700">Notificaciones del sistema</label>
                                    <p class="text-gray-500">Recibe notificaciones sobre mantenimiento y actualizaciones del sistema.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-save mr-1"></i> Guardar preferencias
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar notificación -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="text-center mb-4">
            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar notificación?</h3>
            <p class="text-gray-600">Esta notificación será eliminada permanentemente.</p>
        </div>
        <div class="flex justify-end space-x-3">
            <button id="cancel-delete" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                Cancelar
            </button>
            <form id="delete-form" action="" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar modal de confirmación para eliminar
    function confirmarEliminacion(id) {
        const url = "{{ route('client.notificacion-eliminar', ':id') }}".replace(':id', id);
        document.getElementById('delete-form').action = url;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    document.getElementById('cancel-delete').addEventListener('click', function() {
        document.getElementById('delete-modal').classList.add('hidden');
    });

    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endpush