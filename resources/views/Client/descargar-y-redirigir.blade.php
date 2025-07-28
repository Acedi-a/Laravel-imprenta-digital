@extends('Layouts.LayoutCliente')
@section('title', 'Descargando comprobante...')
@section('content')
<div class="flex flex-col items-center justify-center min-h-screen">
    <h2 class="text-lg font-semibold mb-4">Descargando comprobante...</h2>
    <p class="text-gray-600 mb-2">Si la descarga no inicia automáticamente, <a href="{{ $downloadUrl }}" class="text-blue-600 underline">haz clic aquí</a>.</p>
    <p class="text-gray-500 text-sm">Serás redirigido a tus pedidos en unos segundos.</p>
</div>
<script>
    window.onload = function() {
        window.location.href = "{{ $downloadUrl }}";
        setTimeout(function() {
            window.location.href = "{{ $redirectUrl }}";
        }, 2000);
    };
</script>
@endsection
