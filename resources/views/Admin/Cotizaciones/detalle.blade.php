@extends('Layouts.LayoutAdmin')

@section('title', 'Detalle de Cotización')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cotización #{{ str_pad($cotizacion->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-gray-500 mt-1">Emitida el {{ $cotizacion->created_at->format('d/m/Y') }} a las {{ $cotizacion->created_at->format('H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $cotizacion->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $cotizacion->estado === 'aprobada' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $cotizacion->estado === 'rechazada' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($cotizacion->estado) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Columna Principal --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Producto --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                        </svg>
                        Detalles del Producto
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Producto</p>
                            <p class="text-gray-900">{{ $cotizacion->producto->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipo de Impresión</p>
                            <p class="text-gray-900">{{ $cotizacion->producto->tipo_impresion }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipo de Papel</p>
                            <p class="text-gray-900">{{ $cotizacion->producto->tipo_papel }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Acabado</p>
                            <p class="text-gray-900">{{ $cotizacion->producto->acabado }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Color</p>
                            <p class="text-gray-900">{{ $cotizacion->producto->color }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tamaño</p>
                            <p class="text-gray-900">
                                {{ $cotizacion->producto->tamanoPapel->nombre ?? '-' }}
                                <span class="text-gray-500 text-sm">
                                    ({{ $cotizacion->producto->tamanoPapel->ancho ?? '-' }} × {{ $cotizacion->producto->tamanoPapel->alto ?? '-' }} {{ $cotizacion->producto->tamanoPapel->unidad_medida ?? '' }})
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($cotizacion->producto->descripcion)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-500">Descripción</p>
                            <p class="text-gray-700 mt-1">{{ $cotizacion->producto->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Archivo --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Archivo de Diseño
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if(Str::startsWith($cotizacion->archivo->tipo_mime, 'image'))
                                <img src="{{ asset('storage/' . $cotizacion->archivo->ruta_guardado) }}" alt="Preview" class="w-24 h-24 object-cover rounded-lg">
                            @else
                                <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $cotizacion->archivo->nombre_original }}</p>
                            <p class="text-sm text-gray-500">{{ number_format($cotizacion->archivo->tamaño_archivo, 2) }} KB</p>
                            <a href="{{ asset('storage/' . $cotizacion->archivo->ruta_guardado) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Ver archivo completo →</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            
            {{-- Cliente --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Cliente
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $cotizacion->usuario->nombre }} {{ $cotizacion->usuario->apellido }}</p>
                            <p class="text-sm text-gray-500">{{ $cotizacion->usuario->email }}</p>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $cotizacion->usuario->telefono }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen de Cotización --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Resumen
                    </h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Cantidad</span>
                        <span class="text-sm font-medium text-gray-900">{{ $cotizacion->cantidad }} unidades</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Precio unitario</span>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($cotizacion->precio_total / $cotizacion->cantidad, 2) }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900">Total</span>
                            <span class="text-xl font-bold text-gray-900">${{ number_format($cotizacion->precio_total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="space-y-3">

                    <a href="{{ route('admin.cotizaciones.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 text-sm text-white font-medium rounded-md bg-indigo-600 hover:bg-indigo-200 hover:text-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection