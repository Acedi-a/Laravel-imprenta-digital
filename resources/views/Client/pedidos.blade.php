@extends('Layouts.LayoutCliente')

@section('title', 'Mis Pedidos | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y descripción -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2 text-indigo-800">Mis Pedidos</h1>
        <p class="text-gray-600">Gestiona y realiza seguimiento de todos tus pedidos de impresión.</p>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="rounded-full bg-indigo-100 p-3 mr-4">
                    <i class="fas fa-shopping-cart text-xl text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total de pedidos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Pedidos pendientes -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 mr-4">
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos->where('estado', 'pendiente')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Pedidos en proceso -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <i class="fas fa-spinner text-xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">En proceso</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos->where('estado', 'en_proceso')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Pedidos completados -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Completados</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos->whereIn('estado', ['completado', 'finalizado'])->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form action="{{ route('client.pedidos') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar por número de pedido</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Número de pedido..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div class="w-full md:w-48">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" id="estado" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                    <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="w-full md:w-48">
                <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select name="prioridad" id="prioridad" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todas</option>
                    <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ request('prioridad') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('client.pedidos') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-undo mr-1"></i> Limpiar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de pedidos -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Historial de Pedidos</h2>
        </div>

        @if($pedidos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Pedido</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pedidos as $pedido)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $pedido->numero_pedido }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-md flex items-center justify-center">
                                            <i class="fas fa-print text-indigo-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $pedido->cotizacion->producto->nombre }}</div>
                                            <div class="text-sm text-gray-500">{{ $pedido->cotizacion->cantidad }} unidades</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pedido->fecha_pedido->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pedido->estado == 'pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                    @elseif($pedido->estado == 'en_proceso')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">En proceso</span>
                                    @elseif($pedido->estado == 'completado' || $pedido->estado == 'finalizado')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completado</span>
                                    @elseif($pedido->estado == 'cancelado')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pedido->prioridad == 'baja')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Baja</span>
                                    @elseif($pedido->prioridad == 'media')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Media</span>
                                    @elseif($pedido->prioridad == 'alta')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Alta</span>
                                    @elseif($pedido->prioridad == 'urgente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Urgente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('client.pedido-detalle', $pedido->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver detalles</a>
                                    <a href="{{ route('client.pedido-seguimiento', $pedido->id) }}" class="text-green-600 hover:text-green-900">Seguimiento</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pedidos->withQueryString()->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes pedidos</h3>
                <p class="text-gray-500 mb-4">Cuando realices un pedido, aparecerá aquí para que puedas hacer seguimiento.</p>
                <a href="{{ route('client.cotizaciones') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-file-invoice-dollar mr-1"></i> Ver mis cotizaciones
                </a>
            </div>
        @endif
    </div>

    <!-- Sección de información de proceso -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Paso 1: Cotización -->
        <div class="bg-white rounded-xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
            <div class="text-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-indigo-600 font-bold">1</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">Cotización</h3>
                <p class="text-gray-600 text-sm">Solicita una cotización para tus productos de impresión.</p>
            </div>
        </div>

        <!-- Paso 2: Aprobación -->
        <div class="bg-white rounded-xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
            <div class="text-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-indigo-600 font-bold">2</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">Aprobación</h3>
                <p class="text-gray-600 text-sm">Revisamos tu solicitud y te enviamos una cotización oficial.</p>
            </div>
        </div>

        <!-- Paso 3: Producción -->
        <div class="bg-white rounded-xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
            <div class="text-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-indigo-600 font-bold">3</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">Producción</h3>
                <p class="text-gray-600 text-sm">Iniciamos el proceso de impresión y acabado de tus productos.</p>
            </div>
        </div>

        <!-- Paso 4: Entrega -->
        <div class="bg-white rounded-xl shadow-md p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
            <div class="text-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-indigo-600 font-bold">4</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">Entrega</h3>
                <p class="text-gray-600 text-sm">Enviamos tus productos terminados a la dirección especificada.</p>
            </div>
        </div>
    </div>

    <!-- Sección de ayuda -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-md p-8 mt-8 text-white">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold mb-4">¿Necesitas ayuda con tu pedido?</h2>
                <p class="mb-4">Nuestro equipo de atención al cliente está disponible para resolver cualquier duda sobre el estado de tu pedido.</p>
                <div class="flex space-x-4">
                    <a href="#" class="px-4 py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                        <i class="fas fa-headset mr-1"></i> Contactar soporte
                    </a>
                    <a href="#" class="px-4 py-2 border border-white text-white rounded-lg font-medium hover:bg-white hover:text-indigo-600 transition duration-200">
                        <i class="fas fa-question-circle mr-1"></i> Preguntas frecuentes
                    </a>
                </div>
            </div>
            <div class="md:w-1/3 mt-6 md:mt-0 flex justify-center">
                <img src="https://cdn-icons-png.flaticon.com/512/2706/2706950.png" alt="Soporte" class="w-32 h-32 object-contain filter brightness-0 invert opacity-80">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script para animaciones y efectos adicionales si se necesitan
</script>
@endpush