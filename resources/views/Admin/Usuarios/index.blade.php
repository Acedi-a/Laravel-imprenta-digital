@extends('Layouts.LayoutAdmin')

@section('title', 'Usuarios')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
    <button id="btnNuevo"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
        + Nuevo Usuario
    </button>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($usuarios as $u)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $u->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $u->nombre }} {{ $u->apellido }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $u->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($u->rol) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $u->telefono }}</td> 
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full
                            {{ $u->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $u->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-indigo-600 hover:underline btnEdit"
                                data-json="{{ $u->toJson() }}">Editar</button>
                        <form action="{{ route('admin.usuarios.eliminar', $u->id) }}"
                              method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-red-600 hover:underline"
                                    onclick="return confirm('¿Deseas {{ $u->estado ? 'desactivar' : 'activar' }} este usuario?')">
                                {{ $u->estado ? 'Desactivar' : 'Activar' }}
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
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
        <form id="formUsuario" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nuevo Usuario</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Apellido</label>
                    <input type="text" name="apellido" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="rol" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                        <option value="cliente">Cliente</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div id="passwordGroup">
                    <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                    <small class="text-gray-500">Dejar vacío si no deseas cambiarla.</small>
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
const modal      = document.getElementById('modal');
const form       = document.getElementById('formUsuario');
const method     = document.getElementById('method');
const modalTitle = document.getElementById('modalTitle');
const passwordGroup = document.getElementById('passwordGroup');

function openModal(editData = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.reset();
    if (editData) {
        modalTitle.textContent = 'Actualizar Usuario';
        method.value = 'PUT';
        form.action = `/admin/usuarios/${editData.id}`;
        passwordGroup.style.display = 'block';
        Object.keys(editData).forEach(k => {
            const el = form.querySelector(`[name="${k}"]`);
            if (el) el.value = editData[k];
        });
    } else {
        modalTitle.textContent = 'Nuevo Usuario';
        method.value = 'POST';
        form.action = "{{ route('admin.usuarios.guardar') }}";
        passwordGroup.style.display = 'block';
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