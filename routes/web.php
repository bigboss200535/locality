<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XMLValidationController;
use App\Http\Controllers\ProductController;

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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
