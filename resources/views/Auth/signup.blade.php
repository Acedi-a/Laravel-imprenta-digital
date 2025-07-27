<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro - Imprenta Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-pink-50 min-h-screen flex items-center justify-center py-8">
    <div class="w-full max-w-md bg-white/90 rounded-xl shadow-xl px-8 py-10">
        <div class="flex flex-col items-center mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="5" width="18" height="14" rx="3" fill="currentColor" opacity="0.1" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10a2 2 0 002 2h12a2 2 0 002-2V7M4 7a2 2 0 012-2h12a2 2 0 012 2" />
            </svg>
            <h1 class="text-3xl font-extrabold text-indigo-700 mb-1 tracking-tight">Imprenta Digital</h1>
            <span class="text-sm text-gray-500 mb-1">Crea tu cuenta para comenzar</span>
        </div>

        @if(session('error'))
        <div class="mb-4 px-4 py-2 text-sm rounded bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('signup.post') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
                        class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm" />
                    @error('nombre')
                    <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                    <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required
                        class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm" />
                    @error('apellido')
                    <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                @error('email')
                <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono <span class="text-gray-400">(opcional)</span></label>
                <input id="telefono" type="tel" name="telefono" value="{{ old('telefono') }}"
                    class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                    placeholder="Ej: 70123456" />
                @error('telefono')
                <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input id="password" type="password" name="password" required
                    class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                <div class="mt-1 text-xs text-gray-500">
                    Mínimo 8 caracteres
                </div>
                @error('password')
                <div class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                <div id="password-match" class="mt-1 text-xs hidden"></div>
            </div>

            <button type="submit"
                class="w-full py-2 px-6 rounded-lg bg-gradient-to-r from-indigo-600 via-pink-500 to-indigo-600 text-white font-bold text-lg shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-150">
                Crear cuenta
            </button>
        </form>

        <div class="mt-6 text-center space-y-2">
            <div class="flex items-center justify-center">
                <div class="border-t border-gray-300 flex-grow mr-3"></div>
                <span class="text-gray-500 text-sm">o</span>
                <div class="border-t border-gray-300 flex-grow ml-3"></div>
            </div>
            <p class="text-sm text-gray-600">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Inicia sesión aquí</a>
            </p>
        </div>
    </div>

    <script>
        // Validación de contraseñas en tiempo real
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('password-match');

        function checkPasswordMatch() {
            if (passwordConfirmation.value.length > 0) {
                if (password.value === passwordConfirmation.value) {
                    passwordMatch.textContent = '✓ Las contraseñas coinciden';
                    passwordMatch.className = 'mt-1 text-xs text-green-600';
                    passwordMatch.classList.remove('hidden');
                } else {
                    passwordMatch.textContent = '✗ Las contraseñas no coinciden';
                    passwordMatch.className = 'mt-1 text-xs text-red-600';
                    passwordMatch.classList.remove('hidden');
                }
            } else {
                passwordMatch.classList.add('hidden');
            }
        }

        password.addEventListener('input', checkPasswordMatch);
        passwordConfirmation.addEventListener('input', checkPasswordMatch);

        // Validación de formato de teléfono
        const telefono = document.getElementById('telefono');
        telefono.addEventListener('input', function(e) {
            // Permitir solo números y algunos caracteres especiales
            e.target.value = e.target.value.replace(/[^0-9+\-\s()]/g, '');
        });
    </script>
</body>

</html>
