<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;



Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', function () {
    return view('welcome');
})->middleware(['auth'])->name('dashboard');
