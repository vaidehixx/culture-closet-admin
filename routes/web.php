<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

// Landing page on public domain
Route::domain('culturecloset.site')->group(function () {
    Route::get('/', fn () => view('landing'));
});

// Admin auth routes (admin subdomain)
Route::domain('admin.culturecloset.site')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');
});

// Fallback for local dev / direct IP access
Route::get('/', fn () => redirect()->route('admin.login'));
