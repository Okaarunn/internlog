<?php

use App\Http\Controllers\Admin\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

/*
Authentication routes admin and user
*/

// routes for auth intern
Route::middleware('auth:interns')->group(function () {
    // dashboard
    Route::get('/', function () {
        return view('intern.dashboard');
    })->name('dashboard');

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// routes for guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// routes for auth admin
Route::middleware('auth:admins')->group(function () {

    // dashboard 
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // logout
    Route::post('/admin/logout', [AuthAuthController::class, 'logout'])->name('admin.logout');
});

// routes for guest
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthAuthController::class, 'showLoginForm'])->name('admin-login.show');
    Route::post('/admin/login', [AuthAuthController::class, 'login'])->name('admin-login.submit');
});
