@extends('Layouts/layoutUsuario')
@section('title', 'Home')
@section('header', 'Bienvenido a la página de inicio')
@section('content')
    <p class="text-lg">Este es el contenido de la pagina principal</p>
@endsection

@push('scripts')
    <script>
        console.log('Script específico de la página de inicio cargado.');
    </script>
@endpush