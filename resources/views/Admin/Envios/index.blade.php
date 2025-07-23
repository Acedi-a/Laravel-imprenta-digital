@extends('Layouts.LayoutAdmin')

@section('title', 'Envíos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Envíos</h1>
    <button id="btnNuevo"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
        + Nuevo Envío
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transportista</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seguimiento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($envios as $e)
                <tr>
                    <td class="px-6 py-4">{{ $e->id }}</td>
                    <td class="px-6 py-4">#{{ $e->pedido->numero_pedido }}</td>
                    <td class="px-6 py-4">{{ $e->direccion->linea1 }}, {{ $e->direccion->ciudad }}</td>
                    <td class="px-6 py-4">{{ $e->transportista }}</td>
                    <td class="px-6 py-4">{{ $e->codigo_seguimiento }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 text-xs font-semibold rounded-full
                            {{ $e->estado == 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $e->estado == 'cancelado' ? 'Cancelado' : 'Activo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-indigo-600 hover:underline btnEdit"
                                data-json="{{ $e->toJson() }}">Editar</button>
                        <form action="{{ route('admin.envios.eliminar', $e->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-red-600 hover:underline"
                                    onclick="return confirm('¿{{ $e->estado == 'cancelado' ? 'Reactivar' : 'Cancelar' }} envío?')">
                                {{ $e->estado == 'cancelado' ? 'Reactivar' : 'Cancelar' }}
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
        <form id="formEnvio" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nuevo Envío</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pedido</label>
                    <select name="pedido_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach($pedidos as $p)
                            <option value="{{ $p->id }}">#{{ $p->numero_pedido }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <select name="direccion_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach($direcciones as $d)
                            <option value="{{ $d->id }}">{{ $d->linea1 }}, {{ $d->ciudad }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Transportista</label>
                    <input type="text" name="transportista" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Código de Seguimiento</label>
                    <input type="text" name="codigo_seguimiento" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de Envío</label>
                    <input type="datetime-local" name="fecha_envio" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Estimada de Entrega</label>
                    <input type="datetime-local" name="fecha_estimada_entrega" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
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
const form  = document.getElementById('formEnvio');
const method = document.getElementById('method');
const modalTitle = document.getElementById('modalTitle');

function openModal(editData = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.reset();
    if (editData) {
        modalTitle.textContent = 'Actualizar Envío';
        method.value = 'PUT';
        form.action = `/admin/envios/${editData.id}`;
        ['fecha_envio','fecha_estimada_entrega'].forEach(k => {
            const el = form.querySelector(`[name="${k}"]`);
            if (el) el.value = editData[k]?.replace(' ','T');
        });
        Object.keys(editData).forEach(k => {
            if (!['fecha_envio','fecha_estimada_entrega'].includes(k)) {
                const el = form.querySelector(`[name="${k}"]`);
                if (el) el.value = editData[k];
            }
        });
    } else {
        modalTitle.textContent = 'Nuevo Envío';
        method.value = 'POST';
        form.action = "{{ route('admin.envios.guardar') }}";
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