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
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">Generar comprobante PDF</button>
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
