<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public static string $middlewareGroup = 'role';

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $usuario = Auth::user();


        if (!$usuario || !in_array($usuario->rol, $roles)) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
