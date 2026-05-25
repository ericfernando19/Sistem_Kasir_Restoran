<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
            Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });

        Route::resource('menus', MenuController::class);
        Route::prefix('menus')->group(function () {
            Route::put('/{menu}/toggle-availability', [MenuController::class, 'toggleAvailability'])->name('menus.toggle-availability');
        });

        Route::resource('tables', TableController::class)->except(['show']);
        Route::prefix('tables')->group(function () {
            Route::put('/{table}/toggle-status', [TableController::class, 'toggleStatus'])->name('tables.toggle-status');
        });

        Route::resource('users', UserController::class)->except(['show']);

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/select-table', [OrderController::class, 'selectTable'])->name('orders.select-table');
        Route::post('/add-to-cart', [OrderController::class, 'addToCart'])->name('orders.add-to-cart');
        Route::post('/update-cart', [OrderController::class, 'updateCart'])->name('orders.update-cart');
        Route::post('/remove-from-cart', [OrderController::class, 'removeFromCart'])->name('orders.remove-from-cart');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::get('/clear-cart', [OrderController::class, 'clearCart'])->name('orders.clear-cart');
        Route::get('/{transaction}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
        Route::get('/history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('/{transaction}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/{transaction}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/{transaction}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });
});
