<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\FarmerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ForumsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('shop/product/{id}', [ShopController::class, 'show'])->name('shop.product');

// Forum Routes
Route::get('forums', [ForumsController::class, 'index'])->name('forums.index');
Route::get('forums/create', [ForumsController::class, 'create'])->name('forums.create')->middleware('auth');
Route::post('forums', [ForumsController::class, 'store'])->name('forums.store')->middleware('auth');
Route::get('forums/topic/{id}', [ForumsController::class, 'show'])->name('forums.topic');
Route::get('forums/topic/{id}/edit', [ForumsController::class, 'edit'])->name('forums.edit')->middleware('auth');
Route::put('forums/topic/{id}', [ForumsController::class, 'update'])->name('forums.update')->middleware('auth');
Route::delete('forums/topic/{id}', [ForumsController::class, 'destroy'])->name('forums.destroy')->middleware('auth');
Route::post('forums/topic/{id}/reply', [ForumsController::class, 'storeReply'])->name('forums.reply')->middleware('auth');
Route::post('forums/reply/{id}/helpful', [ForumsController::class, 'markHelpful'])->name('forums.helpful')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

//farmer Dashboard Route
Route::middleware(['auth', 'farmer'])->group(function () {
    Route::get('/farmer/dashboard', [FarmerDashboardController::class, 'index'])->name('farmer.dashboard');
});

//buyer Dashboard Route
Route::middleware(['auth', 'buyer'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
});

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

require __DIR__.'/auth.php';
