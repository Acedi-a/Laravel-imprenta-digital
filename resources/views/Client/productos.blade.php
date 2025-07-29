@extends('Layouts.LayoutCliente')

@section('title', 'Productos | Imprenta Digital')

@section('content')
<div class="container mx-auto">
    <!-- Encabezado con título y descripción -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-indigo-800">Nuestros Productos de Impresión</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">Descubre nuestra amplia gama de productos de impresión de alta calidad. Desde tarjetas de presentación hasta banners de gran formato, tenemos todo lo que necesitas para tu negocio o evento.</p>
    </div>

    <!-- Filtros y ordenamiento -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form action="{{ route('client.productos') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="tipo_impresion" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Impresión</label>
                <select name="tipo_impresion" id="tipo_impresion" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($tiposImpresion as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo_impresion') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tipo_papel" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Papel</label>
                <select name="tipo_papel" id="tipo_papel" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($tiposPapel as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo_papel') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tamano_papel_id" class="block text-sm font-medium text-gray-700 mb-1">Tamaño</label>
                <select name="tamano_papel_id" id="tamano_papel_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($tamanosPapel as $tamano)
                        <option value="{{ $tamano->id }}" {{ request('tamano_papel_id') == $tamano->id ? 'selected' : '' }}>{{ $tamano->nombre }} ({{ $tamano->ancho }}x{{ $tamano->alto }} {{ $tamano->unidad_medida }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                <select name="orden" id="orden" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="recientes" {{ $ordenamiento == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                    <option value="precio_asc" {{ $ordenamiento == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                    <option value="precio_desc" {{ $ordenamiento == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    <option value="nombre" {{ $ordenamiento == 'nombre' ? 'selected' : '' }}>Nombre</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2 mt-2">
                <a href="{{ route('client.productos') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-undo mr-1"></i> Limpiar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ $productos->total() }} productos encontrados</h2>
        </div>

        @if($productos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($productos as $producto)
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 card-hover">
                        <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                            @elseif($producto->tamanoPapel && $producto->tamanoPapel->fotosReferenciales->count() > 0)
                                <img src="{{ asset('storage/' . $producto->tamanoPapel->fotosReferenciales->first()->url) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                    <i class="fas fa-print text-4xl text-indigo-300"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            @if($producto->descuento > 0)
                                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    -{{ $producto->descuento }}%
                                </div>
                            @endif

                            <h3 class="text-lg font-semibold text-gray-800 mb-1 truncate">{{ $producto->nombre }}</h3>
                            
                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <span class="mr-2"><i class="fas fa-print text-indigo-400 mr-1"></i> {{ ucfirst($producto->tipo_impresion) }}</span>
                                <span><i class="fas fa-ruler-combined text-indigo-400 mr-1"></i> {{ $producto->tamanoPapel ? $producto->tamanoPapel->nombre : 'N/A' }}</span>
                            </div>

                            <div class="flex justify-between items-end mt-3">
                                <div>
                                    @if($producto->descuento > 0)
                                        <span class="text-gray-500 line-through text-sm">${{ number_format($producto->precio_base, 2) }}</span>
                                        <span class="text-xl font-bold text-indigo-600">${{ number_format($producto->precio_base * (1 - $producto->descuento / 100), 2) }}</span>
                                    @else
                                        <span class="text-xl font-bold text-indigo-600">${{ number_format($producto->precio_base, 2) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('client.producto-detalle', $producto->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $productos->withQueryString()->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl p-8 text-center">
                <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron productos</h3>
                <p class="text-gray-500 mb-4">Intenta con otros filtros o revisa más tarde.</p>
                <a href="{{ route('client.productos') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-undo mr-1"></i> Ver todos los productos
                </a>
            </div>
        @endif
    </div>

    <!-- Sección de categorías destacadas -->
    <div class="mt-12 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Categorías Destacadas</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Categoría 1 -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105">
                <i class="fas fa-id-card text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Tarjetas de Presentación</h3>
                <p class="mb-4">Impresiones profesionales para destacar tu marca personal o empresarial.</p>
                <a href="#" class="inline-block px-4 py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                    Explorar <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <!-- Categoría 2 -->
            <div class="bg-gradient-to-br from-pink-500 to-red-600 rounded-xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105">
                <i class="fas fa-book-open text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Folletos y Catálogos</h3>
                <p class="mb-4">Materiales impresos de alta calidad para promocionar tus productos y servicios.</p>
                <a href="#" class="inline-block px-4 py-2 bg-white text-pink-600 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                    Explorar <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <!-- Categoría 3 -->
            <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105">
                <i class="fas fa-flag text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Banners y Lonas</h3>
                <p class="mb-4">Impresiones de gran formato para eventos, publicidad exterior y decoración.</p>
                <a href="#" class="inline-block px-4 py-2 bg-white text-amber-600 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                    Explorar <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Sección de ventajas -->
    <div class="bg-white rounded-xl shadow-md p-8 mt-12">
        <h2 class="text-2xl font-bold text-center mb-8 text-gray-800">¿Por qué elegirnos?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Ventaja 1 -->
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-print text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Alta Calidad</h3>
                <p class="text-gray-600">Utilizamos tecnología de punta para garantizar impresiones nítidas y duraderas.</p>
            </div>
            
            <!-- Ventaja 2 -->
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-truck text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Entrega Rápida</h3>
                <p class="text-gray-600">Cumplimos con los plazos de entrega para que tengas tus productos a tiempo.</p>
            </div>
            
            <!-- Ventaja 3 -->
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-tags text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Precios Competitivos</h3>
                <p class="text-gray-600">Ofrecemos la mejor relación calidad-precio del mercado.</p>
            </div>
            
            <!-- Ventaja 4 -->
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-headset text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Soporte Personalizado</h3>
                <p class="text-gray-600">Nuestro equipo está disponible para asesorarte en todo el proceso.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script para animaciones y efectos adicionales si se necesitan
</script>
@endpush