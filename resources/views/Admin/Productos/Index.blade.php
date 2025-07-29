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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Impresión</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio base</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descuento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño papel</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($productos as $p)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->id }}</td>
                <td class="px-6 py-4">
                    @if($p->imagen)
                        <img src="{{ asset('storage/' . $p->imagen) }}" alt="{{ $p->nombre }}" class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                    @else
                        <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->nombre }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->tipo_impresion }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($p->precio_base, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->descuento ? number_format($p->descuento, 2) . '%' : 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    @if($p->tamanoPapel)
                        {{ $p->tamanoPapel->nombre }} ({{ $p->tamanoPapel->ancho }}x{{ $p->tamanoPapel->alto }} {{ $p->tamanoPapel->unidad_medida }})
                    @else
                        N/A
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    @if($p->estado === 'activo')
                        <span class="text-green-600 font-semibold">Activo</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactivo</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                    <button class="btnEdit text-indigo-600 hover:text-indigo-900"
                            data-json="{{ $p->toJson() }}">Editar</button>
                    <form action="{{ route('admin.productos.eliminar', $p->id) }}" method="POST" class="inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Eliminar producto?')">
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
        <form id="formProducto" method="POST" enctype="multipart/form-data">
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
                    <label class="block text-sm font-medium text-gray-700">Tipo de Impresión</label>
                    <select name="tipo_impresion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Digital">Digital</option>
                        <option value="Offset">Offset</option>
                        <option value="Gran formato">Gran formato</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Papel</label>
                    <select name="tipo_papel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccionar...</option>
                        <option value="Couché">Couché</option>
                        <option value="Bond">Bond</option>
                        <option value="Opalina">Opalina</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Acabado</label>
                    <select name="acabado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccionar...</option>
                        <option value="Laminado">Laminado</option>
                        <option value="Troquelado">Troquelado</option>
                        <option value="Barniz">Barniz</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Color</label>
                    <select name="color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccionar...</option>
                        <option value="Color">Color</option>
                        <option value="B/N">Blanco y Negro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tamaño de Papel</label>
                    <select name="tamano_papel_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccionar...</option>
                        @foreach($tamanosPapel as $tamano)
                            <option value="{{ $tamano->id }}">{{ $tamano->nombre }} ({{ $tamano->ancho }}x{{ $tamano->alto }} {{ $tamano->unidad_medida }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad Mínima</label>
                    <input type="number" min="1" name="cantidad_minima" value="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Precio Base</label>
                    <input type="number" step="0.01" name="precio_base" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descuento (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="descuento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Imagen del Producto</label>
                    <input type="file" name="imagen" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                    <div id="imagenPreview" class="mt-2 hidden">
                        <img id="previewImg" src="" alt="Vista previa" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                    </div>
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
const imagenInput = form.querySelector('[name="imagen"]');
const imagenPreview = document.getElementById('imagenPreview');
const previewImg = document.getElementById('previewImg');

function openModal(editData = null) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.reset();
    imagenPreview.classList.add('hidden');
    
    if (editData) {
        modalTitle.textContent = 'Editar Producto';
        method.value = 'PUT';
        Object.keys(editData).forEach(k => {
            const el = form.querySelector(`[name="${k}"]`);
            if (el && k !== 'imagen') el.value = editData[k];
        });
        
        // Mostrar imagen existente si hay una
        if (editData.imagen) {
            previewImg.src = `/storage/${editData.imagen}`;
            imagenPreview.classList.remove('hidden');
        }
        
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
    form.reset();
    imagenPreview.classList.add('hidden');
}

// Vista previa de imagen
imagenInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagenPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        imagenPreview.classList.add('hidden');
    }
});

document.getElementById('btnNuevo').addEventListener('click', () => openModal());
document.getElementById('btnCloseModal').addEventListener('click', closeModal);
document.getElementById('btnCancel').addEventListener('click', closeModal);

document.querySelectorAll('.btnEdit').forEach(btn =>
    btn.addEventListener('click', () => openModal(JSON.parse(btn.dataset.json)))
);
</script>
@endpush
