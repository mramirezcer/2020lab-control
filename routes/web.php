<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/director', function () {
        return response('DIRECTOR_OK');
    })->middleware('rol:DIRECTOR')->name('director.index');

    Route::get('/lider', function () {
        return response('LIDER_OK');
    })->middleware('rol:LIDER_PROYECTO')->name('lider.index');

    Route::get('/programador-b', function () {
        return response('PROGRAMADOR_B_OK');
    })->middleware('rol:PROGRAMADOR_B')->name('programador-b.index');

    Route::get('/administracion', function () {
        return response('ADMINISTRADOR_OK');
    })->middleware('rol:ADMINISTRADOR')->name('administracion.index');
});