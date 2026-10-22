<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FiltrosController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LoginController::class, 'showLogin'])->name('inicio');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


Route::post('/logout', [LoginController::class, 'logout'])->middleware(['auth', 'verified'])->name('logout');

Route::get('/dashboard', function () {
    return view('index');
})->middleware(['auth', 'verified', 'permission:dashboard.ver'])
->name('dashboard');

/**** Rutas para Usuarios ****/
Route::middleware(['auth', 'verified','permission:usuarios.ver',])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'datatable'])->name('datatable.usuarios');
});

Route::post('/usuarios/crear', [UsuarioController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:usuarios.crear'])
    ->name('usuarios.crear');

Route::middleware(['auth', 'verified','permission:usuarios.editar',])->group(function () {
    Route::post('/usuarios/buscar', [UsuarioController::class, 'getById'])->name('usuarios.buscar');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.editar');
    Route::put('/usuarios/{usuario}/password', [UsuarioController::class, 'updatePassword'])->name('usuarios.password');
});

Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'permission:usuarios.eliminar'])
    ->name('usuarios.eliminar');


    /**** Rutas para Filtros ****/
Route::middleware(['auth', 'verified'])
    ->prefix('filtros')
    ->name('filtros.')
    ->controller(FiltrosController::class)
    ->group(function () {
        Route::get('/permisos', 'permisos')->name('permisos');
        Route::get('/roles', 'roles')->name('roles');
    });

/**** Rutas para Permisos ****/
Route::middleware(['auth', 'verified', 'permission:permisos.administrar'])
    ->prefix('permisos')
    ->name('permisos.')
    ->controller(PermisoController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/datatable', 'datatable')->name('datatable');
        Route::post('/buscar', 'getById')->name('buscar');
        Route::post('/', 'store')->name('crear');
        Route::put('/{permiso}', 'update')->name('editar');
        Route::delete('/{permiso}', 'destroy')->name('eliminar');
        Route::post('/datatable', 'datatable')->name('datatable');
    });

/**** Rutas para Roes ****/
Route::middleware(['auth', 'verified', 'permission:roles.administrar'])
    ->prefix('roles')
    ->name('roles.')
    ->controller(RolController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{role}/permisos', 'permisosPorRol')->name('permisos');
        Route::post('/datatable', 'datatable')->name('datatable');
        Route::post('/buscar', 'getById')->name('buscar');
        Route::post('/', 'store')->name('crear');
        Route::put('/{role}', 'update')->name('editar');
        Route::delete('/{role}', 'destroy')->name('eliminar');
    });
