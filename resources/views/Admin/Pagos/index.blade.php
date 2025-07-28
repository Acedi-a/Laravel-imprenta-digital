@extends('Layouts.LayoutAdmin')

@section('title', 'Pagos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Pagos</h1>
    <button id="btnNuevo"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
        + Nuevo Pago
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Pago</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($pagos as $p)
                <tr>
                    <td class="px-6 py-4">{{ $p->id }}</td>
                    <td class="px-6 py-4">#{{ $p->referencia     }}</td>
                    <td class="px-6 py-4">${{ number_format($p->monto, 2) }}</td>
                    <td class="px-6 py-4">{{ ucfirst($p->metodo) }}</td>
                    <td class="px-6 py-4">{{ (new DateTime($p->fecha_pago))->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 text-xs font-semibold rounded-full
                            {{ $p->estado == 'aprobado' ? 'bg-green-100 text-green-800' :
                               ($p->estado == 'rechazado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($p->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-indigo-600 hover:underline btnEdit"
                                data-json="{{ $p->toJson() }}">Editar
                        </button>
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
        <form id="formPago" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nuevo Pago</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pedido</label>
                    <select name="pedido_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach($pedidos as $p)
                            <option value="{{ $p->id }}">#{{ $p->numero_pedido }}</option>
                        @endforeach
                    </select>
                </div>
                -->
                <div class="text-sm text-gray-500 col-span-2 mb-2">
                    <span class="text-xs">(No seleccione pedido aquí. Use la edición de pedido para asociar el pago.)</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Monto</label>
                    <input type="number" step="0.01" name="monto" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Método</label>
                    <select name="metodo" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="efectivo">Efectivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                    <input type="datetime-local" name="fecha_pago" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Referencia (UUID)</label>
                    <input type="text" name="referencia" maxlength="36"
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
const form  = document.getElementById('formPago');
const method = document.getElementById('method');
const modalTitle = document.getElementById('modalTitle');

function openModal(editData = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.reset();
    if (editData) {
        modalTitle.textContent = 'Actualizar Pago';
        method.value = 'PUT';
        form.action = `/admin/pagos/${editData.id}`;
        Object.keys(editData).forEach(k => {
            const el = form.querySelector(`[name="${k}"]`);
            if (el) {
                if (k === 'fecha_pago') {
                    el.value = editData[k].replace(' ','T');
                } else {
                    el.value = editData[k];
                }
            }
        });
    } else {
        modalTitle.textContent = 'Nuevo Pago';
        method.value = 'POST';
        form.action = "{{ route('admin.pagos.guardar') }}";
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