<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración') - Imprenta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            transition: width 0.3s ease;
        }

        .sidebar-expanded {
            width: 256px;
        }

        .sidebar-collapsed {
            width: 72px;
        }

        .main-content {
            transition: margin-left 0.3s ease;
        }

        .nav-link {
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            background-color: #eef2ff;
        }

        .nav-link.active {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }
        
        .nav-link.active .icon {
            color: #4f46e5;
        }

        .nav-link .icon {
            transition: color 0.2s ease;
        }

        .nav-link.active .icon {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <aside id="sidebar" class="sidebar bg-white shadow-lg fixed h-full flex flex-col justify-between sidebar-expanded">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-indigo-600 tracking-wider transition-opacity duration-300">
                        <span class="logo-text">IMP-ADMIN</span>
                    </h2>
                    <button id="toggle-sidebar" class="text-gray-500 hover:text-indigo-600 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                
                <nav class="flex-1 space-y-2">
                    <a href="{{route('admin.dashboard.index')}}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-tachometer-alt fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Dashboard</span>
                    </a>

                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-4 mb-2 nav-text">Gestión</h3>
                    <a href="{{ route('admin.productos.index') }}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-box-open fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Productos</span>
                    </a>
                    <a href="#" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-tags fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Categorías</span>
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-users fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Usuarios</span>
                    </a>

                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-4 mb-2 nav-text">Operaciones</h3>
                    <a href="{{ route('admin.cotizaciones.index') }}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-file-invoice-dollar fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Cotizaciones</span>
                    </a>
                    <a href="{{ route('admin.pedidos.index') }}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-shopping-cart fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Pedidos</span>
                    </a>
                    <a href="{{ route('admin.pagos.index') }}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-credit-card fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Pagos</span>
                    </a>
                    <a href="{{route('admin.envios.index')}}" class="nav-link flex items-center p-3 rounded-xl text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-truck fa-lg w-6 icon transition-transform"></i>
                        <span class="ml-4 nav-text">Envíos</span>
                    </a>
                </nav>

                <div class="mt-8 pt-4 border-t border-gray-200">
                    <form method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center p-3 rounded-xl bg-gray-50 text-gray-600 hover:bg-red-500 hover:text-white transition-colors duration-300 group">
                            <i class="fas fa-sign-out-alt w-6 icon transition-transform group-hover:scale-110"></i>
                            <span class="ml-4 nav-text font-medium">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div id="main-content" class="flex-1 ml-64 flex flex-col">
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between p-4 px-6 border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Panel de Administración')</h1>
                </div>
            </header>

            <main class="flex-1 p-6">
                @yield('content')
            </main>

            <footer class="text-center p-4 text-sm text-gray-500 bg-white border-t border-gray-200">
                &copy; {{ date('Y') }} Imprenta Digital. Admin Panel.
            </footer>
        </div>
    </div>

    @stack('scripts')

    <script>
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const navTexts = document.querySelectorAll('.nav-text');
        const logoText = document.querySelector('.logo-text');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('sidebar-expanded');
            
            mainContent.classList.toggle('ml-64');
            mainContent.classList.toggle('ml-18');

            navTexts.forEach(el => el.classList.toggle('hidden'));
            logoText.classList.toggle('hidden');

            const currentIcon = toggleBtn.querySelector('i');
            if (sidebar.classList.contains('sidebar-collapsed')) {
                currentIcon.classList.remove('fa-bars');
                currentIcon.classList.add('fa-arrow-right');
            } else {
                currentIcon.classList.remove('fa-arrow-right');
                currentIcon.classList.add('fa-bars');
            }
        });
    </script>
</body>

</html>