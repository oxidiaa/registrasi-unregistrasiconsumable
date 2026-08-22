<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnregistrasiController;
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
    Route::post('/form-registrasi/approve', [ItemController::class, 'approveForm'])->name('form-registrasi.approve');
    Route::delete('/form-registrasi/form/delete', [ItemController::class, 'deleteFormChecksheet'])->name('form-registrasi.delete-checksheet');
    Route::delete('/form-registrasi/{id}', [ItemController::class, 'deleteFormItem'])->name('form-registrasi.delete');

    // Form Comments Routes
    Route::post('/form-registrasi/comments', [ItemController::class, 'storeComment'])->name('form-registrasi.comments.store');
    Route::delete('/form-registrasi/comments/{id}', [ItemController::class, 'deleteComment'])->name('form-registrasi.comments.delete');

    // Form Unregistrasi (Unregistrasion / Discontinue) Routes
    Route::get('/form-unregistrasi', [UnregistrasiController::class, 'formUnregistrasi'])->name('form-unregistrasi');
    Route::post('/form-unregistrasi', [UnregistrasiController::class, 'storeFormItem'])->name('form-unregistrasi.store');
    Route::post('/form-unregistrasi/approve', [UnregistrasiController::class, 'approveForm'])->name('form-unregistrasi.approve');
    Route::delete('/form-unregistrasi/form/delete', [UnregistrasiController::class, 'deleteFormChecksheet'])->name('form-unregistrasi.delete-checksheet');
    Route::delete('/form-unregistrasi/{id}', [UnregistrasiController::class, 'deleteFormItem'])->name('form-unregistrasi.delete');
    Route::post('/form-unregistrasi/comments', [UnregistrasiController::class, 'storeComment'])->name('form-unregistrasi.comments.store');
    Route::delete('/form-unregistrasi/comments/{id}', [UnregistrasiController::class, 'deleteComment'])->name('form-unregistrasi.comments.delete');

    // Account Master User Routes
    Route::post('/users', [ItemController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [ItemController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [ItemController::class, 'deleteUser'])->name('users.delete');
    Route::delete('/users/{id}/destroy', [ItemController::class, 'deleteUser'])->name('users.destroy');
});
