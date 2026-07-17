<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;

// Public Guest Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('dashboard');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/{id}/unregister', [ItemController::class, 'unregister'])->name('items.unregister');
    Route::get('/form-registrasi', [ItemController::class, 'formRegistrasi'])->name('form-registrasi');
    Route::post('/form-registrasi', [ItemController::class, 'storeFormItem'])->name('form-registrasi.store');
    Route::delete('/form-registrasi/{id}', [ItemController::class, 'deleteFormItem'])->name('form-registrasi.delete');
});
