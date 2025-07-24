@extends('Layouts.LayoutAdmin')

@section('title', 'Tamaños de Papel')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Tamaños de Papel</h1>
    <button id="btnNuevo"
        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
        + Nuevo Tamaño
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ancho</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidad</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($tamanos as $t)
            <tr>
                <td class="px-6 py-4">{{ $t->id }}</td>
                <td class="px-6 py-4">{{ $t->nombre }}</td>
                <td class="px-6 py-4">{{ $t->alto }}</td>
                <td class="px-6 py-4">{{ $t->ancho }}</td>
                <td class="px-6 py-4">{{ $t->unidad_medida }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <button class="text-indigo-600 hover:underline btnEdit"
                        data-json='@json($t)'>Editar</button>
                    <form action="{{ route('admin.tamanopapel.destroy', $t->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="text-red-600 hover:underline"
                            onclick="return confirm('¿Seguro que deseas eliminar este tamaño?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal --}}
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <form id="formTamano" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nuevo Tamaño de Papel</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alto</label>
                    <input type="number" name="alto" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ancho</label>
                    <input type="number" name="ancho" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unidad de medida</label>
                    <input type="text" name="unidad_medida" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
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
    const modal = document.getElementById('modal');
    const form = document.getElementById('formTamano');
    const method = document.getElementById('method');
    const modalTitle = document.getElementById('modalTitle');

    function openModal(editData = null) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        form.reset();

        if (editData) {
            modalTitle.textContent = 'Editar Tamaño';
            method.value = 'PUT';
            form.action = `/admin/tamanopapel/${editData.id}`;
            for (let k in editData) {
                const el = form.querySelector(`[name="${k}"]`);
                if (el) el.value = editData[k];
            }
        } else {
            modalTitle.textContent = 'Nuevo Tamaño de Papel';
            method.value = 'POST';
            form.action = "{{ route('admin.tamanopapel.store') }}";
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