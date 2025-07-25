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
    Route::patch('/admin/pedidos/{pedido}/estado', [PedidoController::class, 'eliminar'])->name('admin.pedidos.eliminar');


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
    // Inicio
    Route::get('/inicio', [InicioController::class, 'index'])->name('client.inicio');
    
    // Productos
    Route::get('/productos', [App\Http\Controllers\Client\ProductoController::class, 'index'])->name('client.productos');
    Route::get('/producto/{id}', [App\Http\Controllers\Client\ProductoController::class, 'detalle'])->name('client.producto-detalle');
    
    // Cotizaciones
    Route::get('/cotizaciones', [App\Http\Controllers\Client\CotizacionController::class, 'index'])->name('client.cotizaciones');
    Route::get('/cotizacion/crear', [App\Http\Controllers\Client\CotizacionController::class, 'mostrarFormulario'])->name('client.cotizacion-crear');
    Route::post('/cotizacion/crear', [App\Http\Controllers\Client\CotizacionController::class, 'crear'])->name('client.cotizacion-store');
    Route::get('/cotizacion/{id}', [App\Http\Controllers\Client\CotizacionController::class, 'detalle'])
        ->name('client.cotizacion-detalle')
        ->where('id', '[0-9]+');

    //Pedidso
    Route::post('/pedido/crear/{cotizacion}', [App\Http\Controllers\Client\PedidoController::class, 'crear'])
        ->name('client.pedido-crear')  // ← Sin "name:"
        ->where('cotizacion', '[0-9]+');
    Route::get('/pedido/{id}/seguimiento', [App\Http\Controllers\Client\PedidoController::class, 'seguimiento'])
        ->name('client.pedido-seguimiento')
        ->where('id', '[0-9]+');
    Route::get('/pedido/{id}', [App\Http\Controllers\Client\PedidoController::class, 'detalle'])
        ->name('client.pedido-detalle')
        ->where('id', '[0-9]+');
    Route::get('/pedidos', [App\Http\Controllers\Client\PedidoController::class, 'index'])->name('client.pedidos');

    // Archivos
    Route::get('/archivos', [App\Http\Controllers\Client\ArchivoController::class, 'index'])->name('client.archivos');
    Route::post('/archivo/subir', [App\Http\Controllers\Client\ArchivoController::class, 'subir'])->name('client.archivo-subir');
    Route::get('/archivo/{id}/descargar', [App\Http\Controllers\Client\ArchivoController::class, 'descargar'])->name('client.archivo-descargar');
    Route::delete('/archivo/{id}', [App\Http\Controllers\Client\ArchivoController::class, 'eliminar'])->name('client.archivo-eliminar');
    
    // Notificaciones
    Route::get('/notificaciones', [App\Http\Controllers\Client\NotificacionController::class, 'index'])->name('client.notificaciones');
    Route::patch('/notificacion/{id}/leida', [App\Http\Controllers\Client\NotificacionController::class, 'marcarLeida'])->name('client.notificacion-leida');
    Route::patch('/notificaciones/todas-leidas', [App\Http\Controllers\Client\NotificacionController::class, 'marcarTodasLeidas'])->name('client.notificaciones-todas-leidas');
    Route::delete('/notificacion/{id}', [App\Http\Controllers\Client\NotificacionController::class, 'eliminar'])->name('client.notificacion-eliminar');
});
// --- Usuarios ---
