<?php

use App\Http\Controllers\admin\AbsenceManagementController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Intern\AbsenceController;
use App\Http\Controllers\Intern\AuthController as InternAuthController;
use App\Http\Controllers\Intern\DashboardController;
use App\Http\Controllers\Intern\PermissionController;
use App\Http\Controllers\Admin\InternController;
use App\Http\Controllers\admin\PermissionManagementController;
use Illuminate\Support\Facades\Route;

/*
Authentication routes admin and user
*/

// routes for auth intern
Route::middleware('auth:interns')->group(function () {
    // routes/web.php
    Route::get('/', [DashboardController::class, 'index'])->name('intern.dashboard');

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
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])
        ->name('admin.dashboard');

    // departments
    Route::get('/admin/department', [DepartmentController::class, 'index'])->name('admin.department');
    Route::post('/admin/department', [DepartmentController::class, 'store'])->name('admin.department.store');
    Route::put('/admin/department/{id}', [DepartmentController::class, 'update'])->name('admin.department.update');
    Route::delete('/admin/department/{id}', [DepartmentController::class, 'destroy'])->name('admin.department.destroy');

    // absence
    Route::get('/admin/absence', [AbsenceManagementController::class, 'index'])->name('admin.absence');
    Route::put('/admin/absence/{id}', [AbsenceManagementController::class, 'update'])->name('admin.absence.update');

    // permission
    Route::get('/admin/permission', [PermissionManagementController::class, 'index'])->name('admin.permission');
    Route::put('/admin/permission/{id}', [PermissionManagementController::class, 'update'])->name('admin.permission.update');

    // intern
    Route::get('/admin/intern', [InternController::class, 'index'])->name('admin.intern');
    Route::post('/admin/intern', [InternController::class, 'store'])->name('admin.intern.store');
    Route::put('/admin/intern/{id}', [InternController::class, 'update'])->name('admin.intern.update');
    Route::delete('/admin/intern/{id}', [InternController::class, 'destroy'])->name('admin.intern.destroy');

    // logout
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// routes for guest
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin-login.show');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin-login.submit');
});
