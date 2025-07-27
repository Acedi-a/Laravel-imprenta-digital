<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function signupForm()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'apellido' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'telefono' => ['nullable', 'string', 'max:15', 'regex:/^[0-9+\-\s()]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->numbers()],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono debe contener solo números y caracteres válidos.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
        ]);

        try {
            $usuario = Usuario::create([
                'nombre' => ucwords(strtolower(trim($request->nombre))),
                'apellido' => ucwords(strtolower(trim($request->apellido))),
                'email' => strtolower(trim($request->email)),
                'telefono' => $request->telefono ? trim($request->telefono) : null,
                'password' => Hash::make($request->password),
                'rol' => 'cliente',
                'estado' => 'activo',
            ]);

            Auth::login($usuario);

            return redirect()->route('client.inicio')->with('success', '¡Cuenta creada exitosamente! Bienvenido a Imprenta Digital.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ha ocurrido un error al crear la cuenta. Por favor, intenta nuevamente.');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->rol === 'admin') {
                return redirect()->route('admin.dashboard.index');
            } elseif ($user->rol === 'cliente') {
                return redirect()->route('client.inicio');
            } else {
                Auth::logout();
                return back()->with('error', 'Rol no autorizado.');
            }
        }

        return back()->with('error', 'Email o contraseña incorrectos');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
