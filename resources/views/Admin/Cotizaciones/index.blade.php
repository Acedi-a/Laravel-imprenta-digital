@extends('layouts.layoutAdmin')

@section('title', 'Cotizaciones')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Cotizaciones</h1>
    <button id="btnNueva"
        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
        + Nueva Cotización
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($cotizaciones as $c)
            <tr>
                <td class="px-6 py-4">{{ $c->id }}</td>
                <td class="px-6 py-4">{{ $c->usuario->nombre }} {{ $c->usuario->apellido }}</td>
                <td class="px-6 py-4">{{ $c->producto->nombre }}</td>
                <td class="px-6 py-4">{{ $c->cantidad }}</td>
                <td class="px-6 py-4">${{ number_format($c->precio_total, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 text-xs font-semibold rounded-full
                            {{ $c->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' :
                               ($c->estado == 'aprobada' ? 'bg-green-100 text-green-800' :
                               'bg-red-100 text-red-800') }}">
                        {{ ucfirst($c->estado) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <button class="text-indigo-600 hover:underline btnEdit"
                        data-json='@json($c->load("usuario"))'>Editar</button>
                    <form action="{{ route('admin.cotizaciones.eliminar', $c->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        @php
                        $accion = $c->estado === 'cancelada' ? 'Reactivar' : 'Cancelar';
                        @endphp

                        <button class="text-red-600 hover:underline"
                            onclick="return confirm('¿Está seguro que desea {{ $accion }} cotización?')">
                            {{ $accion }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <form id="formCotizacion" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nueva Cotización</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>

                    {{-- Campo oculto para el usuario_id --}}
                    <input type="hidden" name="usuario_id" id="usuario_id">

                    {{-- Select para creación --}}
                    <select id="select_cliente" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option disabled selected>Seleccione un cliente</option>
                        @foreach($usuarios as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }} {{ $u->apellido }}</option>
                        @endforeach
                    </select>

                    {{-- Texto para edición --}}
                    <div id="nombre_cliente"
                        class="mt-1 hidden rounded-md border border-gray-300 bg-gray-100 p-2 text-gray-700">
                    </div>
                </div>

                {{-- Producto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Producto</label>
                    <select name="producto_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach($productos as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Archivo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Archivo</label>
                    <select name="archivo_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach($archivos as $a)
                        <option value="{{ $a->id }}">{{ $a->nombre_original }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Cantidad --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <input type="number" name="cantidad" min="1" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>

                {{-- Ancho --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ancho (cm)</label>
                    <input type="number" step="0.01" name="ancho" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>

                {{-- Alto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alto (cm)</label>
                    <input type="number" step="0.01" name="alto" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>

                {{-- Precio total --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Precio total</label>
                    <input type="number" step="0.01" name="precio_total" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t space-x-2">
                <button type="button" id="btnCancel"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modal');
    const form = document.getElementById('formCotizacion');
    const method = document.getElementById('method');
    const modalTitle = document.getElementById('modalTitle');

    function openModal(editData = null) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        form.reset();

        const usuarioIdInput = document.getElementById('usuario_id');
        const selectCliente = document.getElementById('select_cliente');
        const nombreCliente = document.getElementById('nombre_cliente');

        if (editData) {
            modalTitle.textContent = 'Actualizar Cotización';
            method.value = 'PUT';
            form.action = `/admin/cotizaciones/${editData.id}`;

            Object.keys(editData).forEach(k => {
                const el = form.querySelector(`[name="${k}"]`);
                if (el) el.value = editData[k];
            });

            usuarioIdInput.value = editData.usuario_id;
            nombreCliente.textContent = `${editData.usuario?.nombre ?? ''} ${editData.usuario?.apellido ?? ''}`;

            // Mostrar nombre, ocultar select
            nombreCliente.classList.remove('hidden');
            selectCliente.classList.add('hidden');
        } else {
            modalTitle.textContent = 'Nueva Cotización';
            method.value = 'POST';
            form.action = "{{ route('admin.cotizaciones.guardar') }}";

            // Mostrar select, ocultar nombre
            usuarioIdInput.value = '';
            nombreCliente.textContent = '';
            nombreCliente.classList.add('hidden');
            selectCliente.classList.remove('hidden');
        }
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