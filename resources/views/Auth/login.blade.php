<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Imprenta Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-pink-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white/90 rounded-xl shadow-xl px-8 py-10">
        <div class="flex flex-col items-center mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="5" width="18" height="14" rx="3" fill="currentColor" opacity="0.1" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10a2 2 0 002 2h12a2 2 0 002-2V7M4 7a2 2 0 012-2h12a2 2 0 012 2" />
            </svg>
            <h1 class="text-3xl font-extrabold text-indigo-700 mb-1 tracking-tight">Imprenta Digital</h1>
            <span class="text-sm text-gray-500 mb-1">Bienvenido, por favor inicia sesión</span>
        </div>

        @if(session('error'))
        <div class="mb-4 px-4 py-2 text-sm rounded bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                @error('email')
                <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input id="password" type="password" name="password" required
                    class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                @error('password')
                <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-2 px-6 rounded-lg bg-gradient-to-r from-indigo-600 via-pink-500 to-indigo-600 text-white font-bold text-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-150">
                Entrar
            </button>
        </form>
        
        <div class="mt-6 text-center space-y-2">
            <div class="flex items-center justify-center">
                <div class="border-t border-gray-300 flex-grow mr-3"></div>
                <span class="text-gray-500 text-sm">o</span>
                <div class="border-t border-gray-300 flex-grow ml-3"></div>
            </div>
            <p class="text-sm text-gray-600">
                ¿No tienes una cuenta? 
                <a href="{{ route('signup') }}" class="text-indigo-600 font-medium hover:underline">Regístrate aquí</a>
            </p>
            <a href="#" class="block text-xs text-indigo-600 hover:underline">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</body>

</html>