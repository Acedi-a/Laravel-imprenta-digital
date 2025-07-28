<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Imprenta Digital')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            /* Altura completa de la ventana */
            position: fixed;
            /* Posición fija */
            width: 16rem;
            /* Ancho fijo igual al w-64 de Tailwind */
        }

        .main-content {
            margin-left: 16rem;
            /* Margen izquierdo igual al ancho del sidebar */
            width: calc(100% - 16rem);
            /* Ancho del contenido principal ajustado */
        }

        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            transform: translateX(5px);
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="sidebar bg-white border-r border-gray-200 p-4 flex flex-col justify-between shadow-lg">
            <div>
                <h2 class="text-2xl font-bold mb-6 text-indigo-600 flex items-center">
                    <i class="fas fa-print mr-2"></i> Imprenta
                </h2>
                <nav class="space-y-1">
                    <a href="{{ route('client.inicio') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.inicio') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                    <a href="{{ route('client.productos') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.productos') || request()->routeIs('client.producto-detalle') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-box mr-2"></i> Productos
                    </a>
                    <a href="{{ route('client.cotizaciones') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.cotizaciones') || request()->routeIs('client.cotizacion-detalle') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Mis Cotizaciones
                    </a>
                    <a href="{{ route('client.pedidos') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.pedidos') || request()->routeIs('client.pedido-detalle') || request()->routeIs('client.pedido-seguimiento') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-shopping-cart mr-2"></i> Mis Pedidos
                    </a>
                    <a href="{{ route('client.archivos') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.archivos') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-file-alt mr-2"></i> Mis Archivos
                    </a>
                    <a href="{{ route('client.direcciones.index') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.direcciones.*') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-map-marker-alt mr-2"></i> Mis Direcciones
                    </a>
                    <a href="{{ route('client.notificaciones') }}" class="sidebar-link block px-3 py-2 rounded hover:bg-indigo-100 hover:text-indigo-600 flex items-center {{ request()->routeIs('client.notificaciones') ? 'bg-indigo-100 text-indigo-600 font-medium' : '' }}">
                        <i class="fas fa-bell mr-2"></i> Notificaciones
                        @if(Auth::check() && Auth::user()->notificaciones()->where('leido', false)->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ Auth::user()->notificaciones()->where('leido', false)->count() }}</span>
                        @endif
                    </a>
                </nav>
            </div>
            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded bg-red-500 text-white hover:bg-red-600 transition duration-300 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>
        {{-- Contenido principal --}}
        <main class="main-content p-6">
            {{-- Heºer superior --}}
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