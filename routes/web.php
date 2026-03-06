<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

// routes for auth
Route::middleware('auth:interns')->group(function () {
    Route::get('/', function () {
        return view('intern.dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// routes for guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'watchLogin'])->name('login.watch');
    Route::post('/login', [AuthController::class, 'submitLogin'])->name('login.submit');
});
