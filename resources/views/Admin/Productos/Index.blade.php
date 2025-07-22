{{-- resources/views/admin/productos/index.blade.php --}}
@extends('Layouts.LayoutAdmin')

@section('title', 'Productos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Catálogo de Productos</h1>
    <button id="btnNuevo" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
        + Nuevo Producto
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio base</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ancho / Alto máx (cm)</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($productos as $p)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->id }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->nombre }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->categoria }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($p->precio, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $p->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($p->estado) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->ancho_max }} / {{ $p->alto_max }}</td>
                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                    <button class="btnEdit text-indigo-600 hover:text-indigo-900"
                            data-json="{{ $p->toJson() }}">Editar</button>
                    <form action="{{ route('admin.productos.actualizar', $p->id) }}" method="POST" class="inline-block">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-900" onclick="return confirm('¿Eliminar producto?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <form id="formProducto" method="POST">
            @csrf
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nuevo Producto</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="_method" id="method" value="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input name="nombre" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categoría</label>
                    <input name="categoria" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Precio base</label>
                    <input type="number" step="0.01" name="precio" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo unidad</label>
                    <input name="tipo_unidad" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ancho máx (cm)</label>
                    <input type="number" step="0.01" name="ancho_max" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alto máx (cm)</label>
                    <input type="number" step="0.01" name="alto_max" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                        <option value="agotado">Agotado</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t space-x-2">
                <button type="button" id="btnCancel" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const modal   = document.getElementById('modal');
const form    = document.getElementById('formProducto');
const method  = document.getElementById('method');
const modalTitle = document.getElementById('modalTitle');

function openModal(editData = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.reset();
    if (editData) {
        modalTitle.textContent = 'Editar Producto';
        method.value = 'PUT';
        Object.keys(editData).forEach(k => {
            const el = form.querySelector(`[name="${k}"]`);
            if (el) el.value = editData[k];
        });
        form.action = `/admin/productos/${editData.id}`;
    } else {
        modalTitle.textContent = 'Nuevo Producto';
        method.value = 'POST';
        form.action = "{{ route('admin.productos.guardar') }}";
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