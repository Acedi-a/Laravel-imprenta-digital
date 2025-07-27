@extends('Layouts.LayoutAdmin')

@section('title', 'Cotizaciones')

@section('content')
<div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Cotizaciones</h1>
        <button id="btnNueva"
            class="flex items-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform transition duration-300 hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Cotización
        </button>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $stats = [
                'total' => $cotizaciones->total(),
                'pendientes' => $cotizaciones->where('estado', 'pendiente')->count(),
                'aprobados' => $cotizaciones->where('estado', 'aprobada')->count(),
                'cancelados' => $cotizaciones->where('estado', 'cancelada')->count(),
            ];
        @endphp
        @foreach(['Total'=>'total', 'Pendientes'=>'pendientes', 'Aprobados'=>'aprobados', 'Cancelados'=>'cancelados'] as $label=>$key)
            <div class="bg-white p-6 rounded-lg shadow-md transform transition duration-500 hover:scale-105">
                <p class="text-sm font-medium text-gray-500 truncate">{{ $label }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats[$key] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Filtros -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="relative w-full md:w-64">
                <input id="search" type="text" placeholder="Buscar por cliente o producto..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="flex items-center gap-4">
                <select id="filterEstado" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="aprobada">Aprobadas</option>
                    <option value="cancelada">Canceladas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Papel</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cotizaciones as $cotizacion)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ str_pad($cotizacion->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $cotizacion->usuario->nombre }} {{ $cotizacion->usuario->apellido }}</div>
                                        <div class="text-sm text-gray-500">{{ $cotizacion->usuario->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-sm text-gray-900 font-medium">{{ $cotizacion->producto->nombre }}</div>
                                <div class="text-sm text-gray-500">{{ $cotizacion->producto->descripcion }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $cotizacion->producto->tamanoPapel->nombre }} ({{ $cotizacion->producto->tamanoPapel->ancho }}mm x {{ $cotizacion->producto->tamanoPapel->alto }}mm)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $cotizacion->cantidad }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right font-medium">${{ number_format($cotizacion->precio_total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $cotizacion->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' :
                                       ($cotizacion->estado == 'aprobada' ? 'bg-green-100 text-green-800' :
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($cotizacion->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('admin.cotizaciones.detalle', $cotizacion->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <button class="text-green-600 hover:text-green-900 btnEdit" data-json='@json($cotizacion)' title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.cotizaciones.eliminar', $cotizacion->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="{{ $cotizacion->estado === 'cancelada' ? 'Reactivar' : 'Cancelar' }}" onclick="return confirm('¿Está seguro?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($cotizacion->estado === 'cancelada')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                @endif
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-2 text-sm">No hay cotizaciones aún.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $cotizaciones->links() }}
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <form id="formCotizacion" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 id="modalTitle" class="text-2xl font-semibold text-gray-900">Nueva Cotización</h2>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600 text-2xl transition duration-150 ease-in-out">&times;</button>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <!-- Cliente -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <input type="hidden" name="usuario_id" id="usuario_id">
                    <select id="select_cliente" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                        <option disabled selected>Seleccione un cliente</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }} {{ $usuario->apellido }} – {{ $usuario->email }}</option>
                        @endforeach
                    </select>
                    <div id="nombre_cliente" class="hidden rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 transition duration-150 ease-in-out"></div>
                </div>
                <!-- Producto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                    <select name="producto_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }} ({{ $producto->tipo_impresion }})</option>
                        @endforeach
                    </select>
                </div>
                <!-- Archivo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Archivo</label>
                    <select name="archivo_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                        @foreach($archivos as $archivo)
                            <option value="{{ $archivo->id }}">{{ $archivo->nombre_original }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Cantidad -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                    <input type="number" name="cantidad" min="1" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                </div>
                <!-- Precio total -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio total</label>
                    <input type="number" step="0.01" name="precio_total" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                </div>
                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end px-6 py-4 border-t space-x-3">
                <button type="button" id="btnCancel" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-150 ease-in-out">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150 ease-in-out">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modal');
    const form = document.getElementById('formCotizacion');

    function openModal(editData = null) {
        form.reset();
        if (editData) {
            document.getElementById('modalTitle').textContent = 'Editar Cotización';
            document.getElementById('method').value = 'PUT';
            form.action = `/admin/cotizaciones/${editData.id}`;
            ['id', 'usuario_id', 'producto_id', 'archivo_id', 'cantidad', 'precio_total', 'estado']
                .forEach(k => form.querySelector(`[name="${k}"]`) && (form.querySelector(`[name="${k}"]`).value = editData[k]));
            document.getElementById('usuario_id').value = editData.usuario_id;
            document.getElementById('nombre_cliente').textContent = `${editData.usuario.nombre} ${editData.usuario.apellido}`;
            document.getElementById('nombre_cliente').classList.remove('hidden');
            document.getElementById('select_cliente').classList.add('hidden');
        } else {
            document.getElementById('modalTitle').textContent = 'Nueva Cotización';
            document.getElementById('method').value = 'POST';
            form.action = "{{ route('admin.cotizaciones.guardar') }}";
            document.getElementById('nombre_cliente').classList.add('hidden');
            document.getElementById('select_cliente').classList.remove('hidden');
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('btnNueva').addEventListener('click', () => openModal());
    document.getElementById('btnCloseModal').addEventListener('click', closeModal);
    document.getElementById('btnCancel').addEventListener('click', closeModal);
    document.querySelectorAll('.btnEdit').forEach(btn =>
        btn.addEventListener('click', () => openModal(JSON.parse(btn.dataset.json)))
    );
</script>
@endpush
