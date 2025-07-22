<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Imprenta Digital')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 p-4 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-6 text-indigo-600">Imprenta</h2>
                <nav class="space-y-3">
                    
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Inicio</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Productos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Mis Cotizaciones</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Mis Pedidos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Mis Archivos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Notificaciones</a>

                </nav>
            </div>
            <div class="mt-6">
                <form  method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded bg-red-500 text-white hover:bg-red-600">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <main class="flex-1 p-6">
            {{-- Header superior --}}
            <header class="mb-6">
                <h1 class="text-2xl font-bold">@yield('title', 'Panel del Cliente')</h1>
            </header>

            {{-- Contenido dinámico --}}
            @yield('content')
        </main>
    </div>

    {{-- Footer --}}
    <footer class="text-center p-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} Mi Imprenta Digital. Todos los derechos reservados.
    </footer>

    @stack('scripts')
</body>

</html>