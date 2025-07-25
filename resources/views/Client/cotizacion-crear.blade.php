@extends('Layouts.LayoutCliente')

@section('title', 'Nueva Cotización | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y botón de volver -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-indigo-800">Nueva Cotización</h1>
            <p class="text-gray-600">Solicita una cotización personalizada para tus proyectos de impresión.</p>
        </div>
        <a href="{{ route('client.cotizaciones') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Volver a cotizaciones
        </a>
    </div>

    <!-- Formulario de cotización -->
    <form action="{{ route('client.cotizacion-crear') }}" method="POST" enctype="multipart/form-data" class="mb-12">
        @csrf
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <!-- Paso 1: Seleccionar producto -->
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">1. Selecciona un producto</h3>
            </div>
            <div class="p-6">
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif

                <div class="mb-6">
                    <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                    <select name="producto_id" id="producto_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Selecciona un producto</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }} - {{ $producto->tipo_impresion }} - {{ $producto->tamanoPapel->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('producto_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="producto-info" class="hidden bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 mb-4 md:mb-0">
                            <div id="producto-imagen" class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        </div>
                        <div class="md:w-3/4 md:pl-6">
                            <h4 id="producto-nombre" class="text-xl font-semibold mb-2">Nombre del producto</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Tipo de impresión:</span> <span id="producto-tipo-impresion">-</span></p>
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Tipo de papel:</span> <span id="producto-tipo-papel">-</span></p>
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Tamaño:</span> <span id="producto-tamano">-</span></p>
                                </div>
                                <div>
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Precio base:</span> <span id="producto-precio">-</span></p>
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Tiempo estimado:</span> <span id="producto-tiempo">-</span></p>
                                </div>
                            </div>
                            <div>
                                <h5 class="font-medium mb-2">Descripción:</h5>
                                <p id="producto-descripcion" class="text-gray-600">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paso 2: Detalles de la cotización -->
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">2. Especifica los detalles</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="number" name="cantidad" id="cantidad" min="1" value="{{ old('cantidad', 100) }}" class="flex-1 rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500">unidades</span>
                        </div>
                        @error('cantidad')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="acabados" class="block text-sm font-medium text-gray-700 mb-1">Acabados (opcional)</label>
                        <select name="acabados" id="acabados" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Selecciona un acabado</option>
                            <option value="Laminado mate" {{ old('acabados') == 'Laminado mate' ? 'selected' : '' }}>Laminado mate</option>
                            <option value="Laminado brillante" {{ old('acabados') == 'Laminado brillante' ? 'selected' : '' }}>Laminado brillante</option>
                            <option value="Barniz UV" {{ old('acabados') == 'Barniz UV' ? 'selected' : '' }}>Barniz UV</option>
                            <option value="Troquelado" {{ old('acabados') == 'Troquelado' ? 'selected' : '' }}>Troquelado</option>
                            <option value="Encuadernado" {{ old('acabados') == 'Encuadernado' ? 'selected' : '' }}>Encuadernado</option>
                        </select>
                        @error('acabados')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="comentarios" class="block text-sm font-medium text-gray-700 mb-1">Comentarios adicionales (opcional)</label>
                    <textarea name="comentarios" id="comentarios" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('comentarios') }}</textarea>
                    @error('comentarios')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Paso 3: Archivos adjuntos -->
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <h3 class="text-lg font-semibold text-indigo-800">3. Adjunta tus archivos (opcional)</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex items-center justify-center w-full">
                        <label for="archivos" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Haz clic para subir</span> o arrastra y suelta</p>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG, AI, PSD (MAX. 10MB)</p>
                            </div>
                            <input id="archivos" name="archivos[]" type="file" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png,.ai,.psd" />
                        </label>
                    </div>
                    <div id="file-list" class="mt-4 space-y-2"></div>
                    @error('archivos')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('archivos.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Puedes adjuntar archivos de diseño o referencias para ayudarnos a entender mejor tu proyecto. Si no tienes archivos en este momento, podrás añadirlos más tarde desde la sección "Mis Archivos".
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('client.cotizaciones') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-paper-plane mr-2"></i> Solicitar cotización
            </button>
        </div>
    </form>

    <!-- Información adicional -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-8 text-white mb-8">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold mb-4">¿Cómo funciona el proceso de cotización?</h2>
                <ol class="space-y-2 mb-6">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">1</span>
                        <span>Completa este formulario con los detalles de tu proyecto.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">2</span>
                        <span>Nuestro equipo revisará tu solicitud y calculará el precio.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">3</span>
                        <span>Recibirás una notificación cuando tu cotización esté lista.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold mr-2">4</span>
                        <span>Si estás de acuerdo con el precio, puedes proceder a realizar el pedido.</span>
                    </li>
                </ol>
            </div>
            <div class="md:w-1/3 mt-6 md:mt-0 flex justify-center">
                <img src="https://cdn-icons-png.flaticon.com/512/1356/1356594.png" alt="Proceso de cotización" class="w-40 h-40 object-contain filter brightness-0 invert opacity-80">
            </div>
        </div>
    </div>

    <!-- Preguntas frecuentes -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
            <h3 class="text-lg font-semibold text-indigo-800">Preguntas frecuentes</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <div>
                    <h4 class="font-medium text-lg mb-2">¿Cuánto tiempo tarda en procesarse mi cotización?</h4>
                    <p class="text-gray-600">Normalmente procesamos las cotizaciones en un plazo de 24-48 horas hábiles. Para proyectos más complejos puede tomar un poco más de tiempo.</p>
                </div>
                <div>
                    <h4 class="font-medium text-lg mb-2">¿Puedo modificar mi cotización después de enviarla?</h4>
                    <p class="text-gray-600">Una vez enviada, no podrás modificar la cotización directamente. Sin embargo, puedes contactar con nuestro equipo para solicitar cambios antes de que sea aprobada.</p>
                </div>
                <div>
                    <h4 class="font-medium text-lg mb-2">¿Qué formatos de archivo son aceptados?</h4>
                    <p class="text-gray-600">Aceptamos archivos en formato PDF, JPG, PNG, AI y PSD. Para obtener los mejores resultados, recomendamos archivos en alta resolución (300 dpi) y en formato PDF o AI.</p>
                </div>
                <div>
                    <h4 class="font-medium text-lg mb-2">¿Cuánto tiempo es válida una cotización?</h4>
                    <p class="text-gray-600">Nuestras cotizaciones son válidas por 15 días a partir de la fecha de emisión. Después de este período, es posible que necesites solicitar una nueva cotización.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar información del producto seleccionado
    document.getElementById('producto_id').addEventListener('change', function() {
        const productoId = this.value;
        const productoInfo = document.getElementById('producto-info');
        
        if (productoId) {
            fetch(`/api/productos/${productoId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('producto-nombre').textContent = data.nombre;
                    document.getElementById('producto-tipo-impresion').textContent = data.tipo_impresion;
                    document.getElementById('producto-tipo-papel').textContent = data.tipo_papel;
                    document.getElementById('producto-tamano').textContent = data.tamano_papel.nombre;
                    document.getElementById('producto-precio').textContent = `$${data.precio}`;
                    document.getElementById('producto-tiempo').textContent = data.tiempo_produccion || 'Variable según cantidad';
                    document.getElementById('producto-descripcion').textContent = data.descripcion;
                    
                    if (data.imagen) {
                        document.getElementById('producto-imagen').innerHTML = `<img src="/storage/${data.imagen}" alt="${data.nombre}" class="w-full h-full object-cover rounded-lg">`;
                    } else {
                        document.getElementById('producto-imagen').innerHTML = `<i class="fas fa-image text-gray-400 text-4xl"></i>`;
                    }
                    
                    productoInfo.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    productoInfo.classList.add('hidden');
                });
        } else {
            productoInfo.classList.add('hidden');
        }
    });

    // Mostrar lista de archivos seleccionados
    document.getElementById('archivos').addEventListener('change', function(e) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';
        
        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const fileSize = (file.size / 1024).toFixed(2) + ' KB';
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg';
            
            let fileIcon = 'fa-file';
            if (file.type.includes('image')) fileIcon = 'fa-file-image';
            else if (file.type.includes('pdf')) fileIcon = 'fa-file-pdf';
            
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${fileIcon} text-indigo-500 mr-3"></i>
                    <div>
                        <p class="font-medium text-sm">${file.name}</p>
                        <p class="text-xs text-gray-500">${fileSize}</p>
                    </div>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeFile(${i})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
        }
    });

    // Función para eliminar un archivo de la lista
    function removeFile(index) {
        const input = document.getElementById('archivos');
        const dt = new DataTransfer();
        
        for (let i = 0; i < input.files.length; i++) {
            if (i !== index) {
                dt.items.add(input.files[i]);
            }
        }
        
        input.files = dt.files;
        
        const event = new Event('change');
        input.dispatchEvent(event);
    }
</script>
@endpush