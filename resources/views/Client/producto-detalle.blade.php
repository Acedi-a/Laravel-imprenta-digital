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
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex gap-2 text-sm  breadcrumbs mb-6">
        <p class="text-indigo-700"><a href="{{ route('client.inicio') }}">Inicio</a></p>
        <span> > </span>
        <p class="text-indigo-900"><a href="#">Productos</a></p>
        <span> > </span>
        <p>{{ $producto->nombre }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div>
            <div class="preview-container rounded-2xl overflow-hidden shadow-md mb-6 h-96 flex items-center justify-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-full flex items-center justify-center">
                    <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/no-image.png') }}" 
                         alt="{{ $producto->nombre }}" class="object-cover w-full h-full">
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4">Descripción del producto</h2>
                <div class="prose max-w-none">
                    {!! $producto->descripcion !!}
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-700">Tamaño del papel</h4>
                        <p class="text-lg font-bold">
                            {{ $producto->tamanoPapel->nombre }} ({{ $producto->tamanoPapel->ancho }} x {{ $producto->tamanoPapel->alto }} {{ $producto->tamanoPapel->unidad_medida }})
                        </p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Tipo de impresión</h4>
                        <p class="text-lg font-bold">{{ $producto->tipo_impresion }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Color</h4>
                        <p class="text-lg font-bold">{{ $producto->color }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Estado</h4>
                        <p class="text-lg font-bold text-green-600">{{ $producto->estado }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-6">
                <h1 class="text-3xl font-bold mb-2">{{ $producto->nombre }}</h1>

                <div class="mb-6">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">${{ number_format($producto->precio_base, 2) }}</h3>
                    <p class="text-gray-600">Precio base por unidad</p>
                </div>

                <form action="{{ route('client.producto-cotizar', $producto->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                        <input type="number" name="cantidad" value="1" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sube tu diseño (PDF/JPG/PNG)</label>
                        <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required
                               class="w-full px-4 py-2 border border-dashed border-gray-300 rounded-lg">
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit"
                                class="flex-1 bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300">
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

    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6">Productos relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($productosRelacionados as $relacionado)
            <a href="{{ route('client.producto-detalle', $relacionado->id) }}" class="group">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden transition transform group-hover:-translate-y-1">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Imagen de {{ $relacionado->nombre }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600">{{ $relacionado->nombre }}</h3>
                        <p class="text-indigo-600 font-bold">${{ number_format($relacionado->precio_base, 2) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
