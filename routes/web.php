<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductManagementController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ProductRequisitionController;

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
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::get('products/{product}/show', [ProductController::class, 'show'])->name('products.show');
    // Route::delete('products/{product}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

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

    // Spoilage Management Routes
    Route::get('/spoilages', [ProductManagementController::class, 'spoilages'])->name('spoilages.index');
    Route::post('/spoilages', [ProductManagementController::class, 'storeSpoilage'])->name('spoilages.store');
    Route::delete('/spoilages/{spoilage}', [ProductManagementController::class, 'destroySpoilage'])->name('spoilages.destroy');
    
    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //stores management routes
    Route::get('/stores', [StoresController::class, 'index'])->name('stores.index');
    Route::post('/stores', [StoresController::class, 'store'])->name('stores.store');
    Route::put('/stores/{store}', [StoresController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{store}', [StoresController::class, 'destroy'])->name('stores.destroy');

     //tenant management routes
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');

    // Purchase Order routes
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::put('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

     // suppliers routes
    Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy'])->name('suppliers.destroy');

    // Requisition routes
    Route::get('/requisitions', [ProductRequisitionController::class, 'index'])->name('requisitions.index');
    Route::post('/requisitions', [ProductRequisitionController::class, 'store'])->name('requisitions.store');
    Route::patch('/requisitions/{requisition}/approve', [ProductRequisitionController::class, 'approve'])->name('requisitions.approve');
    Route::delete('/requisitions/{requisition}', [ProductRequisitionController::class, 'destroy'])->name('requisitions.destroy');
});

//Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
