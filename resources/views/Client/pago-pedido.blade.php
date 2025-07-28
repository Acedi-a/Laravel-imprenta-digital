@extends('Layouts.LayoutCliente')

@section('title', 'Pago de Pedido | Imprenta Digital')

@section('content')
<div class="container mx-auto max-w-2xl py-10">
    <div class="bg-white rounded-xl shadow-md p-8">
        <h1 class="text-3xl font-bold text-indigo-800 mb-6">Información de Pago</h1>
        <div class="mb-6">
            <p class="text-gray-700 mb-2">Pedido: <span class="font-semibold">#{{ $pedido->numero_pedido }}</span></p>
            <p class="text-gray-700 mb-2">Total a pagar: <span class="font-semibold text-green-600 text-lg">${{ number_format($pedido->cotizacion->precio_total, 2) }}</span></p>
            <p class="text-gray-700">Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <form id="formPago" method="POST" action="{{ route('client.pago.generar', $pedido->id) }}">
            @csrf
            
            <!-- Selección de dirección de envío -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">📍 Dirección de Envío</h3>
                @if($direcciones->count() > 0)
                    <div class="space-y-3">
                        @foreach($direcciones as $direccion)
                            <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer transition">
                                <input type="radio" name="direccion_id" value="{{ $direccion->id }}" 
                                       {{ $direccion->defecto ? 'checked' : '' }} 
                                       class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" required>
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">
                                        {{ $direccion->linea1 }}
                                        @if($direccion->defecto) 
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Predeterminada
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        @if($direccion->linea2) {{ $direccion->linea2 }}<br> @endif
                                        {{ $direccion->ciudad }}, {{ $direccion->codigo_postal }}<br>
                                        {{ $direccion->pais }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <a href="{{ route('client.direcciones.index') }}" class="text-indigo-600 hover:text-indigo-800">
                            Administrar direcciones
                        </a>
                    </p>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-600 mb-3">No tienes direcciones registradas</p>
                        <a href="{{ route('client.direcciones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-plus mr-2"></i> Agregar dirección
                        </a>
                    </div>
                @endif
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona el método de pago:</label>
                <select name="metodo" id="metodo" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" required>
                    <option value="qr">QR Bancario</option>
                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                    <option value="efectivo">Pago en Efectivo</option>
                </select>
            </div>
            <div id="qr-section" class="mb-6 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Escanea el QR para pagar:</label>
                <img src="{{ asset('img/qr-demo.png') }}" alt="QR Bancario" class="w-48 h-48 mx-auto border rounded-lg">
                <p class="text-xs text-gray-500 text-center mt-2">* Demo QR, reemplazar por integración real.</p>
            </div>
            <div id="tarjeta-section" class="mb-6 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Datos de la tarjeta:</label>
                <input type="text" name="tarjeta_numero" maxlength="19" placeholder="Número de tarjeta" class="w-full mb-2 rounded-lg border-gray-300 shadow-sm">
                <div class="flex gap-2">
                    <input type="text" name="tarjeta_vencimiento" maxlength="5" placeholder="MM/AA" class="w-1/2 rounded-lg border-gray-300 shadow-sm">
                    <input type="text" name="tarjeta_cvc" maxlength="4" placeholder="CVC" class="w-1/2 rounded-lg border-gray-300 shadow-sm">
                </div>
            </div>
            <div id="efectivo-section" class="mb-6 hidden">
                <p class="text-gray-700">Puedes pagar en efectivo en nuestras oficinas o al repartidor. Se generará un ticket PDF con tu orden.</p>
            </div>
            <div class="flex justify-end">
                @if($direcciones->count() > 0)
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">Procesar pago y generar comprobante</button>
                @else
                    <button type="button" disabled class="px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed font-semibold">Debes agregar una dirección primero</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const metodo = document.getElementById('metodo');
    const qrSection = document.getElementById('qr-section');
    const tarjetaSection = document.getElementById('tarjeta-section');
    const efectivoSection = document.getElementById('efectivo-section');
    metodo.addEventListener('change', function() {
        qrSection.classList.add('hidden');
        tarjetaSection.classList.add('hidden');
        efectivoSection.classList.add('hidden');
        if (this.value === 'qr') qrSection.classList.remove('hidden');
        if (this.value === 'tarjeta') tarjetaSection.classList.remove('hidden');
        if (this.value === 'efectivo') efectivoSection.classList.remove('hidden');
    });
    // Mostrar sección inicial
    metodo.dispatchEvent(new Event('change'));
</script>
@endpush
