@extends('Layouts.LayoutCliente')

@section('title', 'Inicio | Cliente')

@section('head')
    <!-- Swiper CDN CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #4f46e5, #ec4899, #f59e0b);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradient 5s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .card-hover {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .custom-swiper-button-next, .custom-swiper-button-prev {
            background-color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            color: #4f46e5;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }
    </style>
@endsection

@section('content')
<!-- HERO -->
<section class="bg-white text-center py-20 px-6">
    <div class="container mx-auto">
        <div class="floating mb-8">
            <i class="fas fa-print text-6xl text-indigo-200"></i>
        </div>
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6">
            Encuentra tu <span class="gradient-text">impresión perfecta</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
            Carga tu diseño, cotiza al instante y recibe en días con la mejor calidad del mercado
        </p>
        <div class="flex justify-center space-x-4">
            <a href="#"
               class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl transition transform hover:scale-105">
                Ver Productos <i class="fas fa-arrow-right ml-2"></i>
            </a>
            <a href="#como-funciona"
               class="inline-flex items-center px-8 py-3 border-2 border-indigo-200 text-indigo-600 font-bold rounded-full hover:bg-indigo-50 transition">
                Cómo funciona <i class="fas fa-question-circle ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div>
                <h3 class="text-3xl font-bold text-indigo-600">10K+</h3>
                <p class="text-gray-600">Clientes satisfechos</p>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-purple-600">50+</h3>
                <p class="text-gray-600">Productos disponibles</p>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-yellow-500">24h</h3>
                <p class="text-gray-600">Soporte continuo</p>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-green-600">99%</h3>
                <p class="text-gray-600">Entregas a tiempo</p>
            </div>
        </div>
    </div>
</section>

<!-- CARDS RÁPIDAS -->
<section class="py-12 bg-white" id="como-funciona">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-12 text-center">Tu espacio de trabajo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Cotizaciones -->
            <div class="card-hover bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-50">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-file-invoice-dollar text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-center">Mis Cotizaciones</h3>
                <p class="text-gray-600 mb-6 text-center">Revisa y gestiona todas tus cotizaciones activas en un solo lugar</p>
                <div class="flex justify-center">
                    <a href="#" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800">
                        Ver todas <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Pedidos -->
            <div class="card-hover bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-50">
                <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-box text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-center">Mis Pedidos</h3>
                <p class="text-gray-600 mb-6 text-center">Sigue el estado de tus impresiones con actualizaciones en tiempo real</p>
                <div class="flex justify-center">
                    <a href="#" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800">
                        Seguir pedidos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Archivos -->
            <div class="card-hover bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-50">
                <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas fa-file-upload text-2xl text-yellow-500"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-center">Mis Archivos</h3>
                <p class="text-gray-600 mb-6 text-center">Gestiona y organiza todos tus archivos subidos para futuros pedidos</p>
                <div class="flex justify-center">
                    <a href="#" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800">
                        Administrar <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SWIPER PRODUCTOS -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-bold">Productos Destacados</h2>
            <div class="flex space-x-4">
                <div class="custom-swiper-button-prev rounded-full shadow flex items-center justify-center">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="custom-swiper-button-next rounded-full shadow flex items-center justify-center">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>

        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach($productos as $p)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 h-full flex flex-col">
                            <div class="h-56 overflow-hidden rounded-t-2xl">
                                <a href="{{ route('client.producto-detalle', $p->id) }}">
                                    <img src="https://via.placeholder.com/400x300?text={{ urlencode($p->nombre) }}" alt="{{ $p->nombre }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                </a>
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-3">
                                    <a href="{{ route('client.producto-detalle', $p->id) }}" class="font-bold text-xl hover:text-indigo-600">
                                        {{ $p->nombre }}
                                    </a>
                                    @if($p->created_at->diffInDays(now()) < 30)
                                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">Nuevo</span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm mb-4 flex-grow">{{ Str::limit($p->descripcion, 80) }}</p>
                                <div class="flex justify-between items-center mt-auto">
                                    <span class="text-indigo-600 font-bold text-2xl">${{ number_format($p->precio_base, 2) }}</span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('client.producto-detalle', $p->id) }}"
                                           class="border border-indigo-200 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-indigo-50 transition">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIOS -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-12 text-center">Lo que dicen nuestros clientes</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="María López" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">María López</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700">"La calidad de impresión es excepcional. He usado varios servicios pero este es definitivamente el mejor en relación calidad-precio."</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Carlos Méndez" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">Carlos Méndez</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700">"Entrega rápida y producto perfecto. La plataforma es muy fácil de usar y el soporte al cliente es excelente."</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Ana Martínez" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">Ana Martínez</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700">"He quedado muy satisfecha con los materiales y la atención al detalle. Volveré a comprar sin duda."</p>
            </div>
        </div>
    </div>
</section>

<!-- NOTIFICACIONES -->
@if($notificaciones->count())
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Notificaciones Recientes</h2>
                <a href="{{ route('client.notificaciones') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver todas</a>
            </div>
            <div class="space-y-4">
                @foreach($notificaciones as $n)
                    <div class="bg-white rounded-xl p-4 flex items-start space-x-4 border border-gray-100 hover:shadow-sm transition">
                        <div class="w-3 h-3 mt-2 rounded-full" style="background-color: {{ $loop->first ? '#ec4899' : ($loop->index == 1 ? '#f59e0b' : '#4f46e5') }}"></div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="font-semibold">{{ $n->titulo }}</p>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $n->mensaje }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA FINAL -->
<section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">¿Listo para comenzar tu próximo proyecto?</h2>
        <p class="text-xl text-indigo-100 max-w-2xl mx-auto mb-8">Sube tu diseño ahora y recibe tu cotización en minutos</p>
        <div class="flex justify-center space-x-4">
            <a href="#"
               class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-full shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <i class="fas fa-upload mr-2"></i> Subir diseño
            </a>
            <a href="#"
               class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-indigo-600 transition">
                <i class="fas fa-phone-alt mr-2"></i> Contactar ventas
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <!-- Swiper CDN JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Swiper('.swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.custom-swiper-button-next',
                    prevEl: '.custom-swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                }
            });
        });
    </script>
@endsection
