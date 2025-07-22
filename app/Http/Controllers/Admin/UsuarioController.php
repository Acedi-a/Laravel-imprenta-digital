<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('id','desc')->paginate(20);
            return view('Admin.Usuarios.index', compact('usuarios'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:usuarios',
            'telefono'  => 'nullable|string|max:50',
            'rol'       => 'required|in:cliente,admin',
            'password'  => 'required|string|min:6',
        ]);

        Usuario::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'rol'      => $request->rol,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario guardado.');
    }

    public function actualizar(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:usuarios,email,'.$usuario->id,
            'telefono'  => 'nullable|string|max:50',
            'rol'       => 'required|in:cliente,admin',
            'password'  => 'nullable|string|min:6',
        ]);

        $data = $request->only(['nombre','apellido','email','telefono','rol']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function eliminar(Usuario $usuario)
    {
        $usuario->update(['estado' => ! $usuario->estado]);
        return back()->with('success', $usuario->estado ? 'Usuario activado.' : 'Usuario desactivado.');
    }
}