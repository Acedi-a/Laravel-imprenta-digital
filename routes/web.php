<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/inicio', [ClienteController::class, 'inicio']);
Route::get('/admin/dashboard', [DashboardController::class, 'dashboard']);

// Productos admin
Route::get('/admin/productos',[ProductoController::class,'index'])->name('admin.productos.index');
Route::post('/admin/productos', [ProductoController::class, 'guardar'])->name('admin.productos.guardar');
Route::put('/admin/productos/{producto}',[ProductoController::class, 'actualizar'])->name('admin.productos.actualizar');

// Usuarios admin
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
Route::post('/admin/usuarios', [UsuarioController::class, 'guardar'])->name('admin.usuarios.guardar');
Route::put('/admin/usuarios/{usuario}', [UsuarioController::class, 'actualizar'])->name('admin.usuarios.actualizar');
Route::patch('/admin/usuarios/{usuario}/estado', [UsuarioController::class, 'eliminar'])->name('admin.usuarios.eliminar');

/*
Route::middleware(['auth', 'rol:cliente'])->group(function () {
    Route::get('/cliente/inicio', [ClienteController::class, 'inicio']);
});


Route::middleware(['auth', 'rol:admin,operador'])->group(function () {
    Route::get('/admin/pedidos', [AdminController::class, 'pedidos']);
});
*/
