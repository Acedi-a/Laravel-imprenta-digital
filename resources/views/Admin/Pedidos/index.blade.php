@extends('layouts.layoutAdmin')

@section('title', 'Pedidos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Pedidos</h1>
    <button id="btnNuevo"
        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
        + Nuevo Pedido
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cotización</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nº Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($pedidos as $p)
            <tr>
                <td class="px-6 py-4">{{ $p->id }}</td>
                <td class="px-6 py-4">#{{ $p->cotizacion->id }} – {{ $p->cotizacion->producto->nombre }}</td>
                <td class="px-6 py-4">{{ $p->numero_pedido }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 text-xs font-semibold rounded-full
                            {{ $p->estado == 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($p->estado) }}
                    </span>
                </td>
                <td class="px-6 py-4">{{ ucfirst($p->prioridad) }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <button class="text-indigo-600 hover:underline btnEdit"
                        data-json="{{ $p->toJson() }}">Editar</button>
                    <form action="{{ route('admin.pedidos.eliminar', $p->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        @php
                        $accion = $p->estado === 'cancelada' ? 'Reactivar' : 'Cancelar';
                        @endphp

                        <button class="text-red-600 hover:underline"
                            onclick="return confirm('¿Está seguro que desea {{ $accion }} pedido?')">
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
        <form id="formPedido" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nuevo Pedido</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cotización</label>
                    <select name="cotizacion_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach($cotizaciones as $c)
                        <option value="{{ $c->id }}">
                            #{{ $c->id }} – {{ $c->producto->nombre }} ({{ $c->usuario->nombre }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nº Pedido</label>
                    <input type="number" name="numero_pedido" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option value="en_proceso">En proceso</option>
                        <option value="finalizado">Finalizado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                    <select name="prioridad" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Notas</label>
                    <textarea name="notas" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"></textarea>
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
    const form = document.getElementById('formPedido');
    const method = document.getElementById('method');
    const modalTitle = document.getElementById('modalTitle');

    function openModal(editData = null) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        form.reset();
        if (editData) {
            modalTitle.textContent = 'Actualizar Pedido';
            method.value = 'PUT';
            form.action = `/admin/pedidos/${editData.id}`;
            Object.keys(editData).forEach(k => {
                const el = form.querySelector(`[name="${k}"]`);
                if (el) el.value = editData[k];
            });
        } else {
            modalTitle.textContent = 'Nuevo Pedido';
            method.value = 'POST';
            form.action = "{{ route('admin.pedidos.guardar') }}";
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('btnNuevo').addEventListener('click', () => openModal());
    document.getElementById('btnCloseModal').addEventListener('click', closeModal);
    document.getElementById('btnCancel').addEventListener('click', closeModal);
    document.querySelectorAll('.btnEdit').forEach(btn =>
        btn.addEventListener('click', () => openModal(JSON.parse(btn.dataset.json)))
    );
</script>
@endpush