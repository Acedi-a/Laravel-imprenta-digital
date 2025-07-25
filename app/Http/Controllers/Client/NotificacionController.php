<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $no_leidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leido', false)
            ->count();

        return view('Client.notificaciones', compact('notificaciones', 'no_leidas'));
    }

    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('usuario_id', Auth::id())
            ->findOrFail($id);

        $notificacion->update(['leido' => true]);

        return redirect()->back()
            ->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('usuario_id', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);

        return redirect()->back()
            ->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }

    public function eliminar($id)
    {
        $notificacion = Notificacion::where('usuario_id', Auth::id())
            ->findOrFail($id);

        $notificacion->delete();

        return redirect()->route('client.notificaciones')
            ->with('success', 'Notificación eliminada correctamente.');
    }
}