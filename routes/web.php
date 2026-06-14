<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\SalesController;
// Routes with no authentications
Route::get('/', function () {
    return view('get-started');
});

Route::get('/login', function () {
    return view('get-started');
});

Route::get('/get-started', function () {
    return view('get-started');
});

Route::get('/forget-password', function () {
    return view('forget-password');
});

Route::get('/create-account', function () {
    return view('register');
});

// Routes with authentications
Route::middleware(['auth', 'verified'])->group(function (){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('products/{product}/show', [ProductController::class, 'show'])->name('products.show');
    Route::delete('products/{product}/delete', [ProductController::class, 'destroy'])->name('products.destroy');

    // Product Category Routes
    Route::get('/product-categories', [ProductCategoryController::class, 'index'])->name('product-categories');
    Route::post('/product-categories', [ProductCategoryController::class, 'store'])->name('product-categories.store');
    Route::put('/product-categories/{category}', [ProductCategoryController::class, 'update'])->name('product-categories.update');
    Route::patch('/product-categories/{category}/toggle-status', [ProductCategoryController::class, 'toggleStatus'])->name('product-categories.toggle-status');
    Route::delete('/product-categories/{category}', [ProductCategoryController::class, 'destroy'])->name('product-categories.destroy');
    
    // Product Prices Routes
    Route::get('/product-prices', [ProductPriceController::class, 'index'])->name('product-prices');
    Route::post('/product-prices', [ProductPriceController::class, 'store'])->name('product-prices.store');
    Route::put('/product-prices/{product}', [ProductPriceController::class, 'update'])->name('product-prices.update');
    Route::delete('/product-prices/{product}', [ProductPriceController::class, 'destroy'])->name('product-prices.destroy');
    
    // Product Inventory / Stock Routes
    Route::get('/inventory', [ProductStockController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [ProductStockController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{product}', [ProductStockController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{product}', [ProductStockController::class, 'destroy'])->name('inventory.destroy');

    // Sales Routes
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    
    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
