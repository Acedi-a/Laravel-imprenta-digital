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


use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->rol === 'admin') {
            return redirect()->route('admin.dashboard.index');
        } elseif ($user->rol === 'cliente') {
            return redirect()->route('client.inicio');
        } else {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Rol no autorizado.');
        }
    }
    return redirect()->route('login');
});

// Rutas de autenticación (solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/signup', [AuthController::class, 'signupForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
});

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
    Route::get('/admin/cotizaciones/{id}', [CotizacionController::class, 'detalle'])->name('admin.cotizaciones.detalle');
    Route::post('/admin/cotizaciones', [CotizacionController::class, 'guardar'])->name('admin.cotizaciones.guardar');
    Route::put('/admin/cotizaciones/{cotizacion}', [CotizacionController::class, 'actualizar'])->name('admin.cotizaciones.actualizar');
    Route::patch('/admin/cotizaciones/{cotizacion}/estado', [CotizacionController::class, 'eliminar'])->name('admin.cotizaciones.eliminar');


    // Pedidos admin
    Route::get('/admin/pedidos', [PedidoController::class, 'index'])->name('admin.pedidos.index');
    Route::get('/admin/pedidos/{id}/detalle', [PedidoController::class, 'detalle'])->name('admin.pedidos.detalle');
    Route::get('/admin/pedidos/{id}/pdf-preview', [PedidoController::class, 'pdfPreview'])->name('admin.pedidos.pdfpreview');
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
    // Descargar comprobante PDF generado
    Route::get('/descargar-comprobante/{path}', function($path) {
        $filePath = base64_decode($path);
        if (!file_exists($filePath)) abort(404);
        return response()->download($filePath, 'comprobante.pdf');
    })->name('client.pago.descargar');
    // Previsualizar comprobante PDF del pago
    Route::get('/pedido/{id}/comprobante', [App\Http\Controllers\Client\PedidoController::class, 'verComprobante'])->name('client.pedido.comprobante')->where('id', '[0-9]+');
    // Página de pago de pedido
    Route::get('/pedido/{id}/pago', [App\Http\Controllers\Client\PagoController::class, 'pagoPedido'])->name('client.pago.pedido')->where('id', '[0-9]+');
    Route::post('/pedido/{id}/pago', [App\Http\Controllers\Client\PagoController::class, 'generarComprobante'])->name('client.pago.generar')->where('id', '[0-9]+');
    // Inicio
    Route::get('/inicio', [InicioController::class, 'index'])->name('client.inicio');
    
    // Productos
    Route::get('/productos', [App\Http\Controllers\Client\ProductoController::class, 'index'])->name('client.productos');
    Route::get('/producto/{id}', [App\Http\Controllers\Client\ProductoController::class, 'detalle'])->name('client.producto-detalle');
    Route::post('/producto/{id}/cotizar', [App\Http\Controllers\Client\CotizacionController::class, 'cotizarDesdeProducto'])->name('client.producto-cotizar');
    
    // Cotizaciones
    Route::get('/cotizaciones', [App\Http\Controllers\Client\CotizacionController::class, 'index'])->name('client.cotizaciones');
    Route::get('/cotizacion/crear', [App\Http\Controllers\Client\CotizacionController::class, 'mostrarFormulario'])->name('client.cotizacion-crear');
    Route::post('/cotizacion/crear', [App\Http\Controllers\Client\CotizacionController::class, 'crear'])->name('client.cotizacion-store');
    Route::get('/cotizacion/{id}', [App\Http\Controllers\Client\CotizacionController::class, 'detalle'])
        ->name('client.cotizacion-detalle')
        ->where('id', '[0-9]+');
    


    //Pedidos
    Route::post('/pedido/crear/{cotizacion}', [App\Http\Controllers\Client\PedidoController::class, 'crear'])
        ->name('client.pedido-crear')  
        ->where('cotizacion', '[0-9]+');
    Route::get('/pedido/{id}/seguimiento', [App\Http\Controllers\Client\PedidoController::class, 'seguimiento'])
        ->name('client.pedido-seguimiento')
        ->where('id', '[0-9]+');
    Route::get('/pedido/{id}/envio', [App\Http\Controllers\Client\EnvioController::class, 'seguimiento'])
        ->name('client.envio-seguimiento')
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
    
    // Direcciones
    Route::get('/direcciones', [App\Http\Controllers\Client\DireccionController::class, 'index'])->name('client.direcciones.index');
    Route::post('/direccion', [App\Http\Controllers\Client\DireccionController::class, 'store'])->name('client.direcciones.store');
    Route::put('/direccion/{id}', [App\Http\Controllers\Client\DireccionController::class, 'update'])->name('client.direcciones.update');
    Route::delete('/direccion/{id}', [App\Http\Controllers\Client\DireccionController::class, 'destroy'])->name('client.direcciones.destroy');
});
// --- Usuarios ---
