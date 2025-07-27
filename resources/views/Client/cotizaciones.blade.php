@extends('Layouts.LayoutCliente')

@section('title', 'Mis Cotizaciones | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y descripción -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2 text-indigo-800">Mis Cotizaciones</h1>
        <p class="text-gray-600">Gestiona tus solicitudes de cotización para productos de impresión.</p>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total de cotizaciones -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="rounded-full bg-indigo-100 p-3 mr-4">
                    <i class="fas fa-file-invoice-dollar text-xl text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total de cotizaciones</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $cotizaciones->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Cotizaciones pendientes -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 mr-4">
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $cotizaciones->where('estado', 'pendiente')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Cotizaciones aprobadas -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Aprobadas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $cotizaciones->where('estado', 'aprobada')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Cotizaciones rechazadas/vencidas -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 mr-4">
                    <i class="fas fa-times-circle text-xl text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rechazadas/Vencidas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $cotizaciones->whereIn('estado', ['rechazada', 'vencida'])->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de nueva cotización -->
    <div class="mb-8 flex justify-end">
        <a href="{{ route('client.cotizacion-crear') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Nueva cotización
        </a>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form action="{{ route('client.cotizaciones') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <!-- Búsqueda por producto -->
            <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar por producto</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nombre del producto..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            

            <!-- Botones de acción -->
            <div class="flex space-x-2">
                <a href="{{ route('client.cotizaciones') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-undo mr-1"></i> Limpiar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de cotizaciones -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Historial de Cotizaciones</h2>
        </div>

        @if($cotizaciones->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cotizaciones as $cotizacion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $cotizacion->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-md flex items-center justify-center">
                                    <i class="fas fa-print text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $cotizacion->producto->nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ $cotizacion->producto->tipo_impresion }} - {{ $cotizacion->producto->tipo_papel }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cotizacion->cantidad }} unidades</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            @if($cotizacion->precio_total)
                            ${{ number_format($cotizacion->precio_total, 2) }}
                            @else
                            <span class="text-gray-500">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($cotizacion->estado == 'pendiente')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @elseif($cotizacion->estado == 'aprobada')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprobada</span>
                            @elseif($cotizacion->estado == 'rechazada')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rechazada</span>
                            @elseif($cotizacion->estado == 'vencida')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Vencida</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($cotizacion->estado == 'aprobada')
                                <a href="{{ route('client.cotizacion-detalle', $cotizacion->id) }}" class="text-green-600 hover:text-green-900 mr-3">Realizar pedido</a>
                            @else
                                <a href="{{ route('client.cotizacion-detalle', $cotizacion->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver detalles</a>
                            @endif
<!-- Modal para realizar pedido -->
<div id="modalPedido" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <form id="formPedido" method="POST">
            @csrf
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-2xl font-semibold text-gray-900">Realizar Pedido</h2>
                <button type="button" onclick="cerrarModalPedido()" class="text-gray-400 hover:text-gray-600 text-2xl transition duration-150 ease-in-out">&times;</button>
            </div>
            <div class="px-6 py-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">¿Desea agregar alguna nota al pedido? (opcional)</label>
                <textarea name="notas" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" placeholder="Ej: Entregar antes del viernes, instrucciones especiales..."></textarea>
            </div>
            <div class="flex justify-end px-6 py-4 border-t space-x-3">
                <button type="button" onclick="cerrarModalPedido()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-150 ease-in-out">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-700 rounded-lg hover:from-green-600 hover:to-green-800 transition duration-150 ease-in-out">Confirmar pedido</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let cotizacionIdSeleccionada = null;
    function abrirModalPedido(cotizacionId) {
        cotizacionIdSeleccionada = cotizacionId;
        const modal = document.getElementById('modalPedido');
        const form = document.getElementById('formPedido');
        form.action = `/cliente/pedidos/crear/${cotizacionId}`;
        form.reset();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function cerrarModalPedido() {
        const modal = document.getElementById('modalPedido');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $cotizaciones->withQueryString()->links() }}
        </div>
        @else
        <div class="p-6 text-center">
            <i class="fas fa-file-invoice-dollar text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes cotizaciones</h3>
            <p class="text-gray-500 mb-4">Explora nuestros productos y solicita una cotización para comenzar.</p>
            <a href="{{ route('client.cotizacion-crear') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-plus-circle mr-1"></i> Nueva cotización
            </a>
        </div>
        @endif
    </div>

    <!-- Sección de información -->
    <div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-8 text-white">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold mb-4">¿Cómo funciona el proceso de cotización?</h2>
                <ol class="space-y-2 mb-6">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">1</span>
                        <span>Solicita una cotización para el producto que necesitas imprimir.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">2</span>
                        <span>Nuestro equipo revisará tu solicitud y calculará el precio.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">3</span>
                        <span>Recibirás una notificación cuando tu cotización esté lista.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">4</span>
                        <span>Si estás de acuerdo con el precio, puedes proceder a realizar el pedido.</span>
                    </li>
                </ol>
                <a href="#" class="inline-block px-4 py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-question-circle mr-1"></i> Preguntas frecuentes
                </a>
            </div>
            <div class="md:w-1/3 mt-6 md:mt-0 flex justify-center">
                <img src="https://cdn-icons-png.flaticon.com/512/1356/1356594.png" alt="Proceso de cotización" class="w-40 h-40 object-contain filter brightness-0 invert opacity-80">
            </div>
        </div>
    </div>

    <!-- Sección de ayuda -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tarjeta 1: Tipos de impresión -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center mb-4">
                <div class="rounded-full bg-blue-100 p-3 mr-3">
                    <i class="fas fa-print text-xl text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold">Tipos de impresión</h3>
            </div>
            <p class="text-gray-600 mb-4">Ofrecemos diversos tipos de impresión para satisfacer tus necesidades, desde offset hasta digital de alta calidad.</p>
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                Ver más <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Tarjeta 2: Materiales disponibles -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center mb-4">
                <div class="rounded-full bg-green-100 p-3 mr-3">
                    <i class="fas fa-layer-group text-xl text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold">Materiales disponibles</h3>
            </div>
            <p class="text-gray-600 mb-4">Contamos con una amplia variedad de papeles y materiales especiales para tus proyectos de impresión.</p>
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                Ver más <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Tarjeta 3: Tiempos de entrega -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center mb-4">
                <div class="rounded-full bg-purple-100 p-3 mr-3">
                    <i class="fas fa-truck text-xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold">Tiempos de entrega</h3>
            </div>
            <p class="text-gray-600 mb-4">Conoce nuestros tiempos estimados de producción y entrega según el tipo de producto y cantidad.</p>
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                Ver más <i class="fas fa-arrow-right ml-1"></i>
            </a>
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
    // Mostrar modal de confirmación para realizar pedido
    // Reemplaza la función confirmarPedido con esto:
    function confirmarPedido(id) {
        // Construye la URL correctamente usando el formato de Laravel
        const url = "{{ route('client.pedido-crear', ['cotizacion' => '__ID__']) }}".replace('__ID__', id);
        document.getElementById('pedido-form').action = url;
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