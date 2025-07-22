<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 p-4 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-6 text-indigo-600">Admin Imprenta</h2>
                <nav class="space-y-2">
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Dashboard</a>

                    <h3 class="text-sm font-semibold text-gray-500 uppercase mt-4">Gestión</h3>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Productos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Categorías</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Usuarios</a>

                    <h3 class="text-sm font-semibold text-gray-500 uppercase mt-4">Operaciones</h3>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Cotizaciones</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Pedidos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Pagos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Envíos</a>
                    <a  class="block px-3 py-2 rounded hover:bg-indigo-100">Archivos</a>

                    <h3 class="text-sm font-semibold text-gray-500 uppercase mt-4">Sistema</h3>
                    <a class="block px-3 py-2 rounded hover:bg-indigo-100">Notificaciones</a>
                </nav>
            </div>

            <div class="mt-6">
                <form method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded bg-red-500 text-white hover:bg-red-600">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <main class="flex-1 p-6">
            {{-- Header superior --}}
            <header class="mb-6 border-b pb-2">
                <h1 class="text-2xl font-bold">@yield('title', 'Panel de Administración')</h1>
            </header>

            {{-- Contenido dinámico --}}
            @yield('content')
        </main>
    </div>

    {{-- Footer --}}
    <footer class="text-center p-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} Imprenta Digital. Admin Panel.
    </footer>

    @stack('scripts')
</body>

</html>