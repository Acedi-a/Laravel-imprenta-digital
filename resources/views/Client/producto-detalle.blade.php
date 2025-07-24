@extends('Layouts.LayoutCliente')

@section('title', 'Detalle del Producto')

@section('head')
<style>
    .custom-radio input:checked + label {
        border-color: #4f46e5;
        background-color: #eef2ff;
    }
    .option-card {
        transition: all 0.3s ease;
    }
    .option-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .preview-container {
        position: relative;
        background: linear-gradient(45deg, #f9fafb 25%, transparent 25%), 
                    linear-gradient(-45deg, #f9fafb 25%, transparent 25%),
                    linear-gradient(45deg, transparent 75%, #f9fafb 75%),
                    linear-gradient(-45deg, transparent 75%, #f9fafb 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }
    .dimension-input:focus {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs mb-6">
        <ul>
            <li><a href="{{ route('client.inicio') }}">Inicio</a></li> 
            <li><a href="#">Productos</a></li> 
            <li class="text-indigo-600 font-medium">{{ $producto->nombre }}</li>
        </ul>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Columna izquierda: Imágenes y descripción -->
        <div>
            <!-- Imagen principal -->
            <div class="preview-container rounded-2xl overflow-hidden shadow-md mb-6 h-96 flex items-center justify-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-full flex items-center justify-center">
                    <span class="text-gray-500 text-lg">Vista previa del producto</span>
                </div>
            </div>

            <!-- Descripción detallada -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4">Descripción del producto</h2>
                <div class="prose max-w-none">
                    {!! $producto->descripcion !!}
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-700">Ancho máximo</h4>
                        <p class="text-lg font-bold">{{ $producto->ancho_max }} cm</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Alto máximo</h4>
                        <p class="text-lg font-bold">{{ $producto->alto_max }} cm</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Tipo de unidad</h4>
                        <p class="text-lg font-bold">{{ $producto->tipo_unidad }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Estado</h4>
                        <p class="text-lg font-bold text-green-600">{{ $producto->estado }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Especificaciones y cotización -->
        <div>
            <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-6">
                <h1 class="text-3xl font-bold mb-2">{{ $producto->nombre }}</h1>
                <div class="flex items-center mb-6">
                    <div class="flex text-yellow-400 mr-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-gray-600">(24 reseñas)</span>
                </div>

                <div class="mb-6">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">${{ number_format($producto->precio, 2) }}</h3>
                    <p class="text-gray-600">Precio base por unidad</p>
                </div>

                <form id="cotizacionForm" action="{{ route('cotizaciones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">

                    <!-- Dimensiones -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Especifica las dimensiones</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ancho (cm)</label>
                                <input type="number" name="ancho" min="1" max="{{ $producto->ancho_max }}" 
                                       class="dimension-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alto (cm)</label>
                                <input type="number" name="alto" min="1" max="{{ $producto->alto_max }}" 
                                       class="dimension-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Cantidad</h3>
                        <div class="flex items-center">
                            <button type="button" id="decrement" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-l-lg">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" name="cantidad" id="cantidad" min="1" value="1" 
                                   class="w-16 text-center px-0 py-2 border-t border-b border-gray-300">
                            <button type="button" id="increment" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-r-lg">
                                <i class="fas fa-plus"></i>
                            </button>
                            <span class="ml-3 text-gray-600">{{ $producto->tipo_unidad }}</span>
                        </div>
                    </div>

                    <!-- Opciones del producto -->
                    @if($opciones->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Personaliza tu producto</h3>
                        <div class="space-y-3">
                            @foreach($opciones as $opcion)
                            <div class="option-card border border-gray-200 rounded-lg p-4">
                                <div class="custom-radio flex items-start">
                                    <input type="radio" name="opcion_id" id="opcion-{{ $opcion->id }}" 
                                           value="{{ $opcion->id }}" class="mt-1">
                                    <label for="opcion-{{ $opcion->id }}" class="ml-3 block w-full">
                                        <div class="flex justify-between">
                                            <span class="font-medium">{{ $opcion->nombre_opcion }}</span>
                                            <span class="text-indigo-600 font-semibold">
                                                + ${{ number_format($opcion->ajuste_precio, 2) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $opcion->valor_opcion }}</p>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Subir archivo -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Sube tu diseño</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer" 
                             onclick="document.getElementById('archivo').click()">
                            <i class="fas fa-cloud-upload-alt text-3xl text-indigo-200 mb-2"></i>
                            <p class="text-gray-600 mb-1">Haz clic para subir tu archivo</p>
                            <p class="text-sm text-gray-500">Formatos: PDF, JPG, PNG (Máx. 10MB)</p>
                            <input type="file" name="archivo" id="archivo" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <div id="fileName" class="mt-2 text-sm text-gray-600"></div>
                    </div>

                    <!-- Resumen de cotización -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold mb-2">Resumen de cotización</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Producto base:</span>
                                <span id="basePrice">${{ number_format($producto->precio, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Opciones:</span>
                                <span id="optionsPrice">$0.00</span>
                            </div>
                            <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between font-bold">
                                <span>Total estimado:</span>
                                <span id="totalPrice">${{ number_format($producto->precio, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:from-indigo-700 hover:to-purple-700 transition duration-300">
                            <i class="fas fa-calculator mr-2"></i> Cotizar ahora
                        </button>
                        <button type="button" 
                                class="w-12 h-12 flex items-center justify-center border border-indigo-200 text-indigo-600 rounded-lg hover:bg-indigo-50">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6">Productos relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($productosRelacionados as $relacionado)
            <a href="{{ route('producto.detalle', $relacionado->id) }}" class="group">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden transition transform group-hover:-translate-y-1">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Imagen de {{ $relacionado->nombre }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600">{{ $relacionado->nombre }}</h3>
                        <p class="text-indigo-600 font-bold">${{ number_format($relacionado->precio, 2) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Actualizar nombre de archivo al seleccionar
    document.getElementById('archivo').addEventListener('change', function(e) {
        const fileName = document.getElementById('fileName');
        if(this.files.length > 0) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = '';
        }
    });

    // Control de cantidad
    document.getElementById('increment').addEventListener('click', function() {
        const cantidadInput = document.getElementById('cantidad');
        cantidadInput.value = parseInt(cantidadInput.value) + 1;
        actualizarPrecio();
    });

    document.getElementById('decrement').addEventListener('click', function() {
        const cantidadInput = document.getElementById('cantidad');
        if(parseInt(cantidadInput.value) > 1) {
            cantidadInput.value = parseInt(cantidadInput.value) - 1;
            actualizarPrecio();
        }
    });

    // Actualizar precio al cambiar opciones
    const opciones = document.querySelectorAll('input[name="opcion_id"]');
    opciones.forEach(opcion => {
        opcion.addEventListener('change', actualizarPrecio);
    });

    // Actualizar precio al cambiar cantidad
    document.getElementById('cantidad').addEventListener('input', actualizarPrecio);

    function actualizarPrecio() {
        const precioBase = {{ $producto->precio }};
        const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
        
        let precioOpciones = 0;
        opciones.forEach(opcion => {
            if(opcion.checked) {
                precioOpciones += parseFloat(opcion.dataset.precio);
            }
        });

        const total = (precioBase + precioOpciones) * cantidad;
        
        document.getElementById('basePrice').textContent = '$' + precioBase.toFixed(2);
        document.getElementById('optionsPrice').textContent = '$' + precioOpciones.toFixed(2);
        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
    }

    // Inicializar precios
    document.addEventListener('DOMContentLoaded', function() {
        // Asignar precio a cada opción
        const opcionesData = @json($opciones);
        opciones.forEach((opcion, index) => {
            if(opcionesData[index]) {
                opcion.dataset.precio = opcionesData[index].ajuste_precio;
            }
        });
        
        actualizarPrecio();
    });
</script>
@endsection