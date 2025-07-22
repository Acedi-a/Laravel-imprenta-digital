<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;

Route::get('/inicio', [ClienteController::class, 'inicio']);
Route::get('/dashboard', [DashboardController::class, 'dashboard']);

/*
Route::middleware(['auth', 'rol:cliente'])->group(function () {
    Route::get('/cliente/inicio', [ClienteController::class, 'inicio']);
});


Route::middleware(['auth', 'rol:admin,operador'])->group(function () {
    Route::get('/admin/pedidos', [AdminController::class, 'pedidos']);
});
*/
