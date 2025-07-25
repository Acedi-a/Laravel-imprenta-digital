@extends('Layouts.LayoutCliente')

@section('title', 'Mis Archivos | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2 text-indigo-800">Mis Archivos</h1>
        <p class="text-gray-600">Gestiona tus archivos para impresión y mantén organizado tu material digital.</p>
    </div>

    <!-- Estadísticas y botón de subida -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="bg-white rounded-xl shadow-md p-6 w-full md:w-auto">
            <div class="flex items-center space-x-8">
                <div class="flex items-center">
                    <div class="rounded-full bg-indigo-100 p-3 mr-3">
                        <i class="fas fa-file-alt text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total de archivos</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $archivos->total() }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-3">
                        <i class="fas fa-hdd text-xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Espacio utilizado</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $espacio_usado }} MB</p>
                    </div>
                </div>
            </div>
        </div>

        <button id="upload-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
            <i class="fas fa-cloud-upload-alt mr-2"></i> Subir nuevo archivo
        </button>
    </div>

    <div id="upload-form" class="bg-white rounded-xl shadow-md p-6 mb-8 hidden">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Subir nuevo archivo</h2>
        <form action="{{ route('client.archivo-subir') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="archivo" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar archivo</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Selecciona un archivo</span>
                                    <input id="file-upload" name="archivo" type="file" class="sr-only" required>
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, AI, PSD, JPG, PNG hasta 50MB
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional)</label>
                        <textarea id="descripcion" name="descripcion" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Describe brevemente el contenido del archivo..."></textarea>
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de archivo</label>
                        <select id="tipo" name="tipo" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="diseno">Diseño para impresión</option>
                            <option value="imagen">Imagen/Fotografía</option>
                            <option value="documento">Documento</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" id="cancel-upload" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-upload mr-1"></i> Subir archivo
                </button>
            </div>
        </form>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form action="{{ route('client.archivos') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <!-- Búsqueda por nombre -->
            <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar por nombre</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nombre del archivo..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div class="w-full md:w-48">
                <label for="tipo_filtro" class="block text-sm font-medium text-gray-700 mb-1">Tipo de archivo</label>
                <select name="tipo" id="tipo_filtro" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    <option value="diseno" {{ request('tipo') == 'diseno' ? 'selected' : '' }}>Diseño</option>
                    <option value="imagen" {{ request('tipo') == 'imagen' ? 'selected' : '' }}>Imagen</option>
                    <option value="documento" {{ request('tipo') == 'documento' ? 'selected' : '' }}>Documento</option>
                    <option value="otro" {{ request('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <!-- Ordenar por -->
            <div class="w-full md:w-48">
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                <select name="sort" id="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    <option value="size_asc" {{ request('sort') == 'size_asc' ? 'selected' : '' }}>Tamaño (menor a mayor)</option>
                    <option value="size_desc" {{ request('sort') == 'size_desc' ? 'selected' : '' }}>Tamaño (mayor a menor)</option>
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="flex space-x-2">
                <a href="{{ route('client.archivos') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-undo mr-1"></i> Limpiar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de archivos -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Mis Archivos</h2>
            <div class="flex space-x-2">
                <button id="view-grid" class="p-2 rounded-md bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition duration-200 active">
                    <i class="fas fa-th"></i>
                </button>
                <button id="view-list" class="p-2 rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 transition duration-200">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        @if($archivos->count() > 0)
        <!-- Vista de cuadrícula  -->
        <div id="grid-view" class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($archivos as $archivo)
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-200 border border-gray-200 flex flex-col">
                <div class="h-40 bg-gray-100 flex items-center justify-center p-4 border-b border-gray-200">
                    @if(in_array($archivo->tipo_mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                    <img src="{{ asset('storage/' . $archivo->ruta_guardado) }}" alt="{{ $archivo->nombre_original }}" class="max-h-full object-contain">
                    @elseif(str_contains($archivo->tipo_mime, 'pdf'))
                    <i class="far fa-file-pdf text-5xl text-red-500"></i>
                    @elseif(str_contains($archivo->tipo_mime, 'photoshop') || str_contains($archivo->nombre_original, '.psd'))
                    <i class="far fa-file-image text-5xl text-blue-500"></i>
                    @elseif(str_contains($archivo->tipo_mime, 'illustrator') || str_contains($archivo->nombre_original, '.ai'))
                    <i class="far fa-file-image text-5xl text-orange-500"></i>
                    @elseif(str_contains($archivo->tipo_mime, 'word') || str_contains($archivo->nombre_original, '.doc'))
                    <i class="far fa-file-word text-5xl text-blue-600"></i>
                    @elseif(str_contains($archivo->tipo_mime, 'excel') || str_contains($archivo->nombre_original, '.xls'))
                    <i class="far fa-file-excel text-5xl text-green-600"></i>
                    @else
                    <i class="far fa-file text-5xl text-gray-500"></i>
                    @endif
                </div>

                <!-- Información del archivo -->
                <div class="p-4 flex-grow">
                    <h3 class="font-medium text-gray-900 truncate mb-1" title="{{ $archivo->nombre_original }}">
                        {{ $archivo->nombre_original }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $archivo->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-500">{{ $archivo->tamaño_archivo_formateado }}</p>
                </div>

                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-between">
                    <a href="{{ route('client.archivo-descargar', $archivo->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                        <i class="fas fa-download"></i> Descargar
                    </a>
                    <button onclick="confirmarEliminacion('{{ $archivo->id }}', '{{ $archivo->nombre_original }}')" class="text-red-600 hover:text-red-800 text-sm">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div id="list-view" class="hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Archivo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($archivos as $archivo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">
                                    @if(in_array($archivo->tipo_mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                    <i class="far fa-file-image text-2xl text-indigo-500"></i>
                                    @elseif(str_contains($archivo->tipo_mime, 'pdf'))
                                    <i class="far fa-file-pdf text-2xl text-red-500"></i>
                                    @elseif(str_contains($archivo->tipo_mime, 'photoshop') || str_contains($archivo->nombre_original, '.psd'))
                                    <i class="far fa-file-image text-2xl text-blue-500"></i>
                                    @elseif(str_contains($archivo->tipo_mime, 'illustrator') || str_contains($archivo->nombre_original, '.ai'))
                                    <i class="far fa-file-image text-2xl text-orange-500"></i>
                                    @elseif(str_contains($archivo->tipo_mime, 'word') || str_contains($archivo->nombre_original, '.doc'))
                                    <i class="far fa-file-word text-2xl text-blue-600"></i>
                                    @elseif(str_contains($archivo->tipo_mime, 'excel') || str_contains($archivo->nombre_original, '.xls'))
                                    <i class="far fa-file-excel text-2xl text-green-600"></i>
                                    @else
                                    <i class="far fa-file text-2xl text-gray-500"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900" title="{{ $archivo->nombre_original }}">
                                        {{ Str::limit($archivo->nombre_original, 30) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $archivo->tipo_mime }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $archivo->tamaño_archivo_formateado }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $archivo->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('client.archivo-descargar', $archivo->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                            <button onclick="confirmarEliminacion('{{ $archivo->id }}', '{{ $archivo->nombre_original }}')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $archivos->withQueryString()->links() }}
        </div>
        @else
        <div class="p-6 text-center">
            <i class="fas fa-file-upload text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes archivos</h3>
            <p class="text-gray-500 mb-4">Sube tus primeros archivos para comenzar a gestionar tu material de impresión.</p>
            <button id="empty-upload-btn" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-cloud-upload-alt mr-1"></i> Subir mi primer archivo
            </button>
        </div>
        @endif
    </div>

    <!-- Sección de información -->
    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Información sobre archivos</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-5 rounded-lg">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-green-100 p-3 mr-3">
                            <i class="fas fa-check-circle text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold">Formatos aceptados</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li><i class="fas fa-file-pdf text-red-500 mr-2"></i> PDF (recomendado)</li>
                        <li><i class="fas fa-file-image text-blue-500 mr-2"></i> PSD (Photoshop)</li>
                        <li><i class="fas fa-file-image text-orange-500 mr-2"></i> AI (Illustrator)</li>
                        <li><i class="fas fa-file-image text-indigo-500 mr-2"></i> JPG, PNG, TIFF</li>
                        <li><i class="fas fa-file-word text-blue-600 mr-2"></i> DOC, DOCX</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-5 rounded-lg">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-yellow-100 p-3 mr-3">
                            <i class="fas fa-lightbulb text-xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold">Recomendaciones</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Resolución mínima 300 DPI</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Incluir sangrado de 3mm</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Convertir textos a curvas</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Usar modo de color CMYK</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Incluir todas las fuentes</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-5 rounded-lg">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-red-100 p-3 mr-3">
                            <i class="fas fa-exclamation-triangle text-xl text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold">Limitaciones</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li><i class="fas fa-times text-red-500 mr-2"></i> Tamaño máximo: 50MB por archivo</li>
                        <li><i class="fas fa-times text-red-500 mr-2"></i> No se aceptan archivos ejecutables</li>
                        <li><i class="fas fa-times text-red-500 mr-2"></i> Evitar archivos comprimidos</li>
                        <li><i class="fas fa-times text-red-500 mr-2"></i> No usar RGB para impresión offset</li>
                        <li><i class="fas fa-times text-red-500 mr-2"></i> Evitar imágenes de baja resolución</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar archivo -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="text-center mb-4">
            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar archivo?</h3>
            <p class="text-gray-600" id="delete-file-name"></p>
        </div>
        <p class="text-gray-600 mb-6">Esta acción no se puede deshacer. El archivo será eliminado permanentemente de nuestros servidores.</p>
        <div class="flex justify-end space-x-3">
            <button id="cancel-delete" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                Cancelar
            </button>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <p>¿Estás seguro de que deseas eliminar el archivo <strong id="delete-file-name"></strong>?</p>
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="cancel-delete" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-danger">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('upload-btn').addEventListener('click', function() {
        document.getElementById('upload-form').classList.toggle('hidden');
    });

    const emptyUploadBtn = document.getElementById('empty-upload-btn');
    if (emptyUploadBtn) {
        emptyUploadBtn.addEventListener('click', function() {
            document.getElementById('upload-form').classList.remove('hidden');
            window.scrollTo({
                top: document.getElementById('upload-form').offsetTop - 100,
                behavior: 'smooth'
            });
        });
    }

    document.getElementById('cancel-upload').addEventListener('click', function() {
        document.getElementById('upload-form').classList.add('hidden');
    });

    // Cambiar entre vista de cuadrícula y lista
    document.getElementById('view-grid').addEventListener('click', function() {
        document.getElementById('grid-view').classList.remove('hidden');
        document.getElementById('list-view').classList.add('hidden');
        this.classList.add('bg-indigo-100', 'text-indigo-600');
        this.classList.remove('bg-gray-100', 'text-gray-600');
        document.getElementById('view-list').classList.add('bg-gray-100', 'text-gray-600');
        document.getElementById('view-list').classList.remove('bg-indigo-100', 'text-indigo-600');
    });

    document.getElementById('view-list').addEventListener('click', function() {
        document.getElementById('grid-view').classList.add('hidden');
        document.getElementById('list-view').classList.remove('hidden');
        this.classList.add('bg-indigo-100', 'text-indigo-600');
        this.classList.remove('bg-gray-100', 'text-gray-600');
        document.getElementById('view-grid').classList.add('bg-gray-100', 'text-gray-600');
        document.getElementById('view-grid').classList.remove('bg-indigo-100', 'text-indigo-600');
    });

    function confirmarEliminacion(id, nombre) {
        document.getElementById('delete-file-name').textContent = nombre;

        const url = "{{ route('client.archivo-eliminar', ['id' => '__ID__']) }}".replace('__ID__', id);
        document.getElementById('delete-form').action = url;

        document.getElementById('delete-modal').classList.remove('hidden');
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Vista previa del archivo seleccionado
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Ningún archivo seleccionado';
        const fileSize = e.target.files[0]?.size || 0;
        const fileSizeFormatted = fileSize ? (fileSize < 1024 * 1024 ? Math.round(fileSize / 1024) + ' KB' : Math.round(fileSize / (1024 * 1024) * 10) / 10 + ' MB') : '';

        const fileInfo = document.createElement('div');
        fileInfo.className = 'mt-2 text-sm text-indigo-600';
        fileInfo.innerHTML = `<i class="fas fa-file mr-1"></i> ${fileName} ${fileSizeFormatted ? `(${fileSizeFormatted})` : ''}`;

        const existingInfo = document.querySelector('.file-info');
        if (existingInfo) {
            existingInfo.remove();
        }

        fileInfo.classList.add('file-info');
        e.target.parentNode.parentNode.appendChild(fileInfo);
    });
</script>
@endpush