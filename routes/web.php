<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

// Landing page on bare domain
Route::domain('culturecloset.site')->group(function () {
    Route::get('/', fn () => view('landing'));
});

// Admin auth routes (reachable on any domain)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');
});

// Fallback: redirect root to admin login
Route::get('/', fn () => redirect()->route('admin.login'));
