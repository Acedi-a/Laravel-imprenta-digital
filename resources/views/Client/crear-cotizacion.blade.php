@extends('Layouts.LayoutCliente')

@section('title', 'Nueva Cotización')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Nueva Cotización</h1>

            <form action="{{ route('client.cotizacion-store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Selección de Producto -->
                <div class="mb-6">
                    <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Producto *
                    </label>
                    <select name="producto_id" id="producto_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona un producto</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('producto_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cantidad -->
                <div class="mb-6">
                    <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Cantidad *
                    </label>
                    <input type="number" name="cantidad" id="cantidad" min="1" required
                        value="{{ old('cantidad') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('cantidad')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subir Archivo -->
                <div class="mb-6">
                    <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo de diseño
                    </label>
                    <input type="file" name="archivo" id="archivo"
                        accept=".pdf,.jpg,.jpeg,.png,.ai,.eps,.svg"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">
                        Formatos permitidos: PDF, JPG, PNG, AI, EPS, SVG
                    </p>
                    @error('archivo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notas adicionales -->
                <div class="mb-6">
                    <label for="notas" class="block text-sm font-medium text-gray-700 mb-2">
                        Notas adicionales
                    </label>
                    <textarea name="notas" id="notas" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Especifica cualquier detalle importante sobre tu pedido...">{{ old('notas') }}</textarea>
                    @error('notas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('client.cotizaciones') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Solicitar Cotización
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection