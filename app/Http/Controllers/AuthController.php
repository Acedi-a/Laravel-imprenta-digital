<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
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
