@extends('Layouts.LayoutAdmin')

@section('title', 'Envíos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Envíos</h1>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transportista</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($envios as $e)
                <tr>
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">ENV-{{ str_pad($e->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">#{{ $e->pedido->numero_pedido }}</div>
                        <div class="text-sm text-gray-500">{{ $e->created_at->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $e->pedido->cotizacion->usuario->nombre }}</div>
                        <div class="text-sm text-gray-500">{{ $e->pedido->cotizacion->usuario->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($e->pedido->direccion)
                            <div class="text-sm text-gray-900">{{ $e->pedido->direccion->direccion }}</div>
                            <div class="text-sm text-gray-500">{{ $e->pedido->direccion->ciudad }}@if($e->pedido->direccion->pais), {{ $e->pedido->direccion->pais }}@endif</div>
                        @else
                            <div class="text-sm text-gray-500 italic">Sin dirección</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $e->transportista }}</div>
                        @if($e->estado !== 'pendiente')
                            <div class="text-xs text-gray-500">Enviado: {{ $e->fecha_envio ? \Carbon\Carbon::parse($e->fecha_envio)->format('d/m/Y H:i') : '-' }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($e->estado == 'pendiente') bg-yellow-100 text-yellow-800
                            @elseif($e->estado == 'en_camino') bg-blue-100 text-blue-800
                            @elseif($e->estado == 'entregado') bg-green-100 text-green-800
                            @elseif($e->estado == 'cancelado') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($e->estado == 'pendiente') Pendiente
                            @elseif($e->estado == 'en_camino') En camino
                            @elseif($e->estado == 'entregado') Entregado
                            @elseif($e->estado == 'cancelado') Cancelado
                            @else {{ ucfirst($e->estado) }} @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-indigo-600 hover:underline btnEdit"
                                data-envio-id="{{ $e->id }}"
                                data-estado="{{ $e->estado }}"
                                data-transportista="{{ $e->transportista ?: 'No asignado' }}"
                                data-fecha-estimada="{{ $e->fecha_estimada_entrega ? \Carbon\Carbon::parse($e->fecha_estimada_entrega)->format('Y-m-d') : '' }}"
                                data-pedido="{{ $e->pedido->numero_pedido }}"
                                data-direccion="{{ $e->pedido->direccion ? $e->pedido->direccion->direccion : 'Sin dirección' }}">
                            Gestionar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de Gestión de Envío -->
<div id="modal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
        <form id="formEnvio" method="POST">
            @csrf
            @method('PUT')
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold">Gestionar Envío</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 text-2xl">&times;</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <!-- Información no editable -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                    <h4 class="font-medium text-gray-800">Información del Pedido</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Pedido:</span>
                            <span id="infoPedido" class="font-medium"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Código Seguimiento:</span>
                            <span id="infoSeguimiento" class="font-mono text-xs bg-gray-200 px-2 py-1 rounded"></span>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Dirección:</span>
                        <span id="infoDireccion" class="font-medium"></span>
                    </div>
                </div>

                <!-- Campos editables -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transportista *</label>
                    <input type="text" name="transportista" required 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ej: DHL, FedEx, Correos de Bolivia">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Envío *</label>
                    <select name="estado" id="selectEstado" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="pendiente">🕒 Pendiente</option>
                        <option value="en_camino">🚛 En camino</option>
                        <option value="entregado">✅ Entregado</option>
                        <option value="cancelado">❌ Cancelado</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <strong>Pendiente:</strong> Preparando envío • 
                        <strong>En camino:</strong> Se registra fecha de envío automáticamente
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Estimada de Entrega</label>
                    <input type="date" name="fecha_estimada_entrega" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Fecha y hora estimada para la entrega</p>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t space-x-2">
                <button type="button" id="btnCancel"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Actualizar Envío
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const modal = document.getElementById('modal');
const form = document.getElementById('formEnvio');

function openModal(data) {
    console.log('Datos recibidos:', data); // Debug
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Configurar action del formulario
    form.action = `/admin/envios/${data.envioId}`;
    
    // Mostrar información no editable
    document.getElementById('infoPedido').textContent = `#${data.pedido}`;
    document.getElementById('infoSeguimiento').textContent = `ENV-${String(data.envioId).padStart(6, '0')}`;
    document.getElementById('infoDireccion').textContent = data.direccion;
    
    // Rellenar campos editables
    form.querySelector('[name="transportista"]').value = data.transportista;
    form.querySelector('[name="estado"]').value = data.estado;
    
    const fechaInput = form.querySelector('[name="fecha_estimada_entrega"]');
    console.log('Fecha recibida:', data.fechaEstimada); // Debug
    fechaInput.value = data.fechaEstimada || '';
    console.log('Fecha asignada al input:', fechaInput.value); // Debug
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Reset completo del formulario
    form.reset();
    form.querySelector('[name="transportista"]').value = '';
    form.querySelector('[name="estado"]').value = 'pendiente';
    form.querySelector('[name="fecha_estimada_entrega"]').value = '';
}

// Event listeners
document.getElementById('btnCloseModal').addEventListener('click', closeModal);
document.getElementById('btnCancel').addEventListener('click', closeModal);

document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.addEventListener('click', () => {
        const data = {
            envioId: btn.dataset.envioId,
            estado: btn.dataset.estado,
            transportista: btn.dataset.transportista,
            fechaEstimada: btn.dataset.fechaEstimada,
            pedido: btn.dataset.pedido,
            direccion: btn.dataset.direccion
        };
        openModal(data);
    });
});

// Cerrar modal al hacer clic fuera
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});
</script>
@endpush