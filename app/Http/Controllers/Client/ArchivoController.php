<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ArchivoController extends Controller
{
    public function index()
    {
        $archivos = Archivo::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $espacio_usado = Archivo::where('usuario_id', Auth::id())
            ->sum('tamaño_archivo'); 
        $espacio_usado = round($espacio_usado / 1024, 2);

        return view('Client.archivos', compact('archivos', 'espacio_usado'));
    }

    public function subir(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240', // 10MB máximo
        ]);

        $file = $request->file('archivo');
        $nombreOriginal = $file->getClientOriginalName();
        $tipoMime = $file->getMimeType();
        $tamanoArchivo = $file->getSize();

        // Guardar el archivo
        $rutaGuardado = $file->store('archivos/' . Auth::id(), 'public');

        // Crear registro - convertir bytes a KB (dividir entre 1024)
        $archivo = new Archivo([
            'usuario_id' => Auth::id(),
            'nombre_original' => $nombreOriginal,
            'ruta_guardado' => $rutaGuardado,
            'tamaño_archivo' => round($tamanoArchivo / 1024, 2), // Guardar en KB
            'tipo_mime' => $tipoMime
        ]);

        $archivo->save();

        return redirect()->route('client.archivos')
            ->with('success', 'Archivo subido correctamente.');
    }

    public function eliminar($id)
    {
        $archivo = Archivo::where('usuario_id', Auth::id())
            ->findOrFail($id);

        // Eliminar el archivo físico
        if (Storage::disk('public')->exists($archivo->ruta_guardado)) {
            Storage::disk('public')->delete($archivo->ruta_guardado);
        }

        // Eliminar el registro
        $archivo->delete();

        return redirect()->route('client.archivos')
            ->with('success', 'Archivo eliminado correctamente.');
    }


    public function descargar($id)
    {
        $archivo = Archivo::where('usuario_id', Auth::id())
            ->findOrFail($id);

        $path = Storage::disk('public')->path($archivo->ruta_guardado);

        if (!file_exists($path)) {
            return redirect()->route('client.archivos')
                ->with('error', 'El archivo no existe.');
        }

        return response()
            ->download($path, $archivo->nombre_original)
            ->deleteFileAfterSend(true);    }
}