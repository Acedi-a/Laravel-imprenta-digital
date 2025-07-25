<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\CotizacionController;
use App\Http\Controllers\Admin\PagoController;
use App\Http\Controllers\Admin\EnvioController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\TamanoPapelController;

use App\Http\Controllers\Client\InicioController;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    Route::get('/admin/dashboard/pdf', [DashboardController::class, 'pdf'])->name('admin.dashboard.pdf');

    // Productos admin
    Route::get('/admin/productos', [ProductoController::class, 'index'])->name('admin.productos.index');
    Route::post('/admin/productos', [ProductoController::class, 'guardar'])->name('admin.productos.guardar');
    Route::put('/admin/productos/{producto}', [ProductoController::class, 'actualizar'])->name('admin.productos.actualizar');
    Route::delete('/admin/productos/{producto}', [ProductoController::class, 'eliminar'])->name('admin.productos.eliminar');

    // Tamaño papel
    Route::get('/admin/tamanopapel', [TamanoPapelController::class, 'index'])->name('admin.tamanopapel.index');
    Route::post('/admin/tamanopapel', [TamanoPapelController::class, 'store'])->name('admin.tamanopapel.store');
    Route::put('/admin/tamanopapel/{id}', [TamanoPapelController::class, 'update'])->name('admin.tamanopapel.update');
    Route::patch('/admin/tamanopapel/{id}', [TamanoPapelController::class, 'destroy'])->name('admin.tamanopapel.destroy');


    // Usuarios admin
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::post('/admin/usuarios', [UsuarioController::class, 'guardar'])->name('admin.usuarios.guardar');
    Route::put('/admin/usuarios/{usuario}', [UsuarioController::class, 'actualizar'])->name('admin.usuarios.actualizar');
    Route::patch('/admin/usuarios/{usuario}/estado', [UsuarioController::class, 'eliminar'])->name('admin.usuarios.eliminar');

    // Cotizaciones admin
    Route::get('/admin/cotizaciones', [CotizacionController::class, 'index'])->name('admin.cotizaciones.index');
    Route::post('/admin/cotizaciones', [CotizacionController::class, 'guardar'])->name('admin.cotizaciones.guardar');
    Route::put('/admin/cotizaciones/{cotizacion}', [CotizacionController::class, 'actualizar'])->name('admin.cotizaciones.actualizar');
    Route::patch('/admin/cotizaciones/{cotizacion}/estado', [CotizacionController::class, 'eliminar'])->name('admin.cotizaciones.eliminar');

    // Pedidos admin
    Route::get('/admin/pedidos', [PedidoController::class, 'index'])->name('admin.pedidos.index');
    Route::post('/admin/pedidos', [PedidoController::class, 'guardar'])->name('admin.pedidos.guardar');
    Route::put('/admin/pedidos/{pedido}', [PedidoController::class, 'actualizar'])->name('admin.pedidos.actualizar');
    Route::patch('/pedidos/{pedido}/estado', [PedidoController::class, 'eliminar'])->name('admin.pedidos.eliminar');


    // Pagos admin
    Route::get('/admin/pagos', [PagoController::class, 'index'])->name('admin.pagos.index');
    Route::post('/admin/pagos', [PagoController::class, 'guardar'])->name('admin.pagos.guardar');
    Route::put('/admin/pagos/{pago}', [PagoController::class, 'actualizar'])->name('admin.pagos.actualizar');
    Route::patch('/admin/pagos/{pago}/estado', [PagoController::class, 'eliminar'])->name('admin.pagos.eliminar');

    // Envíos admin
    Route::get('/admin/envios', [EnvioController::class, 'index'])->name('admin.envios.index');
    Route::post('/admin/envios', [EnvioController::class, 'guardar'])->name('admin.envios.guardar');
    Route::put('/admin/envios/{envio}', [EnvioController::class, 'actualizar'])->name('admin.envios.actualizar');
    Route::patch('/admin/envios/{envio}/estado', [EnvioController::class, 'eliminar'])->name('admin.envios.eliminar');
    
});



Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/inicio', [InicioController::class, 'index'])->name('client.inicio');
    // Detalle de producto
    Route::get('/producto/{id}', [App\Http\Controllers\Client\ProductoController::class, 'detalle'])->name('client.producto-detalle');
});
// --- Usuarios ---
