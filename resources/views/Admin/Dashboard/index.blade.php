@extends('Layouts.LayoutAdmin')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
@endpush

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Cards resumen -->
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-700">Productos</h3>
        <p class="text-3xl font-bold text-indigo-600">{{ $stats['productos'] }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-700">Usuarios</h3>
        <p class="text-3xl font-bold text-green-600">{{ $stats['usuarios'] }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-700">Cotizaciones</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['cotizaciones'] }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-700">Pedidos</h3>
        <p class="text-3xl font-bold text-red-600">{{ $stats['pedidos'] }}</p>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-2">Estado de Pedidos</h3>
        <canvas id="chartPedidos" height="200"></canvas>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-2">Ingresos Mensuales</h3>
        <canvas id="chartIngresos" height="200"></canvas>
    </div>
</div>

<!-- Tablas recientes -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Últimas cotizaciones -->
    <div class="bg-white p-4 rounded shadow">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold">Últimas Cotizaciones</h3>
            <a href="{{ route('admin.dashboard.pdf', ['tabla' => 'cotizaciones']) }}"
               class="text-indigo-600 hover:underline">Descargar PDF</a>
        </div>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th>ID</th><th>Cliente</th><th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ultimasCotizaciones as $c)
                    <tr>
                        <td class="text-center">{{ $c->id }}</td>
                        <td class="text-center">{{ $c->usuario->nombre }}</td>
                        <td class="text-center">${{ number_format($c->precio_total,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Últimos pagos -->
    <div class="bg-white p-4 rounded shadow">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold">Últimos Pagos</h3>
            <a href="{{ route('admin.dashboard.pdf', ['tabla' => 'pagos']) }}"
               class="text-indigo-600 hover:underline">Descargar PDF</a>
        </div>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th>ID</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ultimosPagos as $p)
                    <tr>
                        <td class="text-center">{{ $p->id }}</td>
                        <td class="text-center">${{ number_format($p->monto,2) }}</td>
                        <td class="text-center">{{ (new DateTime($p->fecha_pago))->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Botones globales -->
<div class="flex justify-end space-x-4">
    <a href="{{ route('admin.dashboard.pdf', ['tabla' => 'all']) }}"
       class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
        Descargar Dashboard Completo (PDF)
    </a>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Datos desde el backend
const pedidosEstados = @json($pedidosEstados);
const ingresos = @json($ingresos);

// Gráfico Estado de Pedidos
new Chart(document.getElementById('chartPedidos'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(pedidosEstados),
        datasets: [{
            data: Object.values(pedidosEstados),
            backgroundColor: ['#10B981','#F59E0B','#EF4444','#9CA3AF']
        }]
    }
});

// Gráfico Ingresos Mensuales
new Chart(document.getElementById('chartIngresos'), {
    type: 'line',
    data: {
        labels: Object.keys(ingresos),
        datasets: [{
            label: 'Ingresos',
            data: Object.values(ingresos),
            fill: false,
            borderColor: '#6366F1',
            tension: 0.2
        }]
    }
});
</script>
@endpush