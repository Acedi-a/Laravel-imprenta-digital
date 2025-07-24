<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InicioController extends Controller
{
    public function index()
    {
        $productos = Producto::where('estado', 'activo')->orderBy('id', 'desc')->limit(8)->get();
        $notificaciones = Auth::check()
            ? Notificacion::where('usuario_id', Auth::id())
                          ->where('leido', 0)
                          ->orderBy('created_at', 'desc')
                          ->limit(3)
                          ->get()
            : collect();

        return view('Client.inicio', compact('productos', 'notificaciones'));
    }
}