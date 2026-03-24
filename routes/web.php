<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Intern\AbsenceController;
use App\Http\Controllers\Intern\PermissionController;
use App\Http\Controllers\Intern\AuthController as InternAuthController;
use Illuminate\Support\Facades\Route;

/*
Authentication routes admin and user
*/

// routes for auth intern
Route::middleware('auth:interns')->group(function () {
    // routes/web.php
    Route::get('/', [AbsenceController::class, 'index'])->name('intern.dashboard');

    // logout
    Route::post('/logout', [InternAuthController::class, 'logout'])->name('logout');

    Route::post('/checkin', [AbsenceController::class, 'checkin'])->name('checkin');
    Route::post('/checkout', [AbsenceController::class, 'checkout'])->name('checkout');
    Route::post('/permission', [PermissionController::class, 'store'])->name('permission.store');
});

// routes for guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [InternAuthController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [InternAuthController::class, 'login'])->name('login.submit');
});


// routes for auth admin
Route::middleware('auth:admins')->group(function () {

    // dashboard 
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // logout
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// routes for guest
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin-login.show');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin-login.submit');
});
