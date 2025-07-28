@extends('Layouts.LayoutCliente')

@section('title', 'Mis Direcciones | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Mostrar mensajes de éxito/error -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-indigo-800">Mis Direcciones</h1>
            <p class="text-gray-600">Administra tus direcciones de entrega</p>
        </div>
        <button id="btnNueva" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i> Nueva Dirección
        </button>
    </div>

    <!-- Lista de direcciones -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($direcciones as $direccion)
            <div class="bg-white rounded-xl shadow-md p-6 relative">
                @if($direccion->defecto)
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-star mr-1"></i> Predeterminada
                        </span>
                    </div>
                @endif
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Dirección #{{ $direccion->id }}</h3>
                    <div class="text-gray-600 space-y-1">
                        <p><strong>Dirección:</strong> {{ $direccion->linea1 }}</p>
                        @if($direccion->linea2)
                            <p><strong>Referencia:</strong> {{ $direccion->linea2 }}</p>
                        @endif
                        <p><strong>Ciudad:</strong> {{ $direccion->ciudad }}</p>
                        <p><strong>Código postal:</strong> {{ $direccion->codigo_postal }}</p>
                        <p><strong>País:</strong> {{ $direccion->pais }}</p>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button class="btnEditar flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition duration-200"
                            data-direccion="{{ json_encode($direccion) }}">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </button>
                    <form action="{{ route('client.direcciones.destroy', $direccion->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition duration-200"
                                onclick="return confirm('¿Estás seguro de eliminar esta dirección?')">
                            <i class="fas fa-trash mr-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <i class="fas fa-map-marker-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No tienes direcciones registradas</h3>
                    <p class="text-gray-600 mb-6">Agrega tu primera dirección de entrega para tus pedidos</p>
                    <button id="btnPrimera" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i> Agregar dirección
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <form id="formDireccion" method="POST" action="{{ route('client.direcciones.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Nueva Dirección</h3>
                <button type="button" id="btnCerrar" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección principal *</label>
                    <input type="text" name="linea1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Calle, número, colonia">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referencia (opcional)</label>
                    <input type="text" name="linea2" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Entre calles, referencias">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                        <input type="text" name="ciudad" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Código postal *</label>
                        <input type="text" name="codigo_postal" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">País *</label>
                    <input type="text" name="pais" required value="Bolivia"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="defecto" id="defecto" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="defecto" class="ml-2 block text-sm text-gray-900">Marcar como dirección predeterminada</label>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t space-x-3">
                <button type="button" id="btnCancelar" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition duration-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
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
const form = document.getElementById('formDireccion');
const method = document.getElementById('method');
const modalTitle = document.getElementById('modalTitle');

// Agregar event listener para debug
form.addEventListener('submit', function(e) {
    console.log('Formulario enviado');
    console.log('Action:', form.action);
    console.log('Method:', method.value);
    
    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
});

function openModal(direccion = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    if (direccion) {
        modalTitle.textContent = 'Editar Dirección';
        method.value = 'PUT';
        form.action = `{{ url('/direccion') }}/${direccion.id}`;
        
        form.querySelector('[name="linea1"]').value = direccion.linea1 || '';
        form.querySelector('[name="linea2"]').value = direccion.linea2 || '';
        form.querySelector('[name="ciudad"]').value = direccion.ciudad || '';
        form.querySelector('[name="codigo_postal"]').value = direccion.codigo_postal || '';
        form.querySelector('[name="pais"]').value = direccion.pais || '';
        form.querySelector('[name="defecto"]').checked = direccion.defecto || false;
    } else {
        modalTitle.textContent = 'Nueva Dirección';
        method.value = 'POST';
        form.action = '{{ route("client.direcciones.store") }}';
        form.reset();
        form.querySelector('[name="pais"]').value = 'Bolivia';
        // Asegurar que el método esté en POST para nuevas direcciones
        form.querySelector('[name="_method"]').value = 'POST';
    }
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    form.reset();
}

document.getElementById('btnNueva').addEventListener('click', () => openModal());
document.getElementById('btnPrimera')?.addEventListener('click', () => openModal());
document.getElementById('btnCerrar').addEventListener('click', closeModal);
document.getElementById('btnCancelar').addEventListener('click', closeModal);

document.querySelectorAll('.btnEditar').forEach(btn => {
    btn.addEventListener('click', () => {
        const direccion = JSON.parse(btn.dataset.direccion);
        openModal(direccion);
    });
});
</script>
@endpush
