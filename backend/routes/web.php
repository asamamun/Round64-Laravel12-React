<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BrandController;

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (no authentication required)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [DashboardController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [DashboardController::class, 'login']);
        Route::get('/register', [DashboardController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [DashboardController::class, 'register']);
    });

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
        
        // User management routes
        Route::resource('users', UserController::class);
        
        // Product management routes
        Route::resource('products', ProductController::class);
        
        // Category management routes
        Route::resource('categories', CategoryController::class);
        
        // Order management routes
        Route::resource('orders', OrderController::class);
        
        // Brand management routes
        Route::resource('brands', BrandController::class);
    });
});

Route::get('/', function () {
    return view('welcome');
});
