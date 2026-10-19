<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FiltrosController;
use App\Http\Controllers\PermisoController;
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

Route::middleware(['auth', 'verified','permission:usuarios.ver',])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'datatable'])->name('datatable.usuarios');
});

/**** Rutas para Usuarios ****/
Route::post('/agregarUsuario', [UsuarioController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:usuarios.crear'])
    ->name('usuario.crear');

Route::middleware(['auth', 'verified','permission:usuarios.editar',])->group(function () {

    Route::post('/searchUsuario', [UsuarioController::class, 'getById'])->name('usuario.buscar');
    Route::put('/usuario/{usuario}', [UsuarioController::class, 'update'])->name('usuario.editar');
    Route::post('/updatePassword', [UsuarioController::class, 'update_password'])->name('usuario.password');
});

Route::delete('/eliminarUsuario', [UsuarioController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'permission:usuarios.eliminar'])
    ->name('usuario.eliminar');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('filtros/roles', [FiltrosController::class, 'roles'])->name('filtros.roles');
});

/**** Rutas para Permisos ****/
Route::middleware(['auth', 'verified','permission:permisos.administrar',])->group(function () {

    Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
    Route::post('/permisos', [PermisoController::class, 'datatable'])->name('datatable.permisos');
    Route::post('/searchUsuario', [PermisoController::class, 'getById'])->name('permiso.buscar');
    Route::post('/agregarPermiso', [PermisoController::class, 'store'])->name('permiso.crear');
    Route::put('/editarPermiso/', [PermisoController::class, 'update'])->name('permiso.editar');
    Route::delete('/eliminarPermiso', [PermisoController::class, 'destroy'])->name('permiso.eliminar');
});

