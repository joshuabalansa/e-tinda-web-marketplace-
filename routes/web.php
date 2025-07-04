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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;

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
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
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
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Farmer Product Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('farmer/products', ProductController::class)->names([
        'index' => 'farmer.products.index',
        'create' => 'farmer.products.create',
        'store' => 'farmer.products.store',
        'show' => 'farmer.products.show',
        'edit' => 'farmer.products.edit',
        'update' => 'farmer.products.update',
        'destroy' => 'farmer.products.destroy',
    ]);
});

require __DIR__.'/auth.php';
