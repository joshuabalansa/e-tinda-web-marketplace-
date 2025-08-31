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
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FarmerOrderController;
use App\Http\Controllers\BuyerOrderController;
use App\Http\Controllers\BuyerWishlistController;

// Language switching route
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hil'])) {
        // Set locale in cookie for persistence
        cookie()->queue('locale', $locale, 60 * 24 * 365); // 1 year

        // Set locale in session as backup
        session()->put('locale', $locale);

        // Set locale in application
        app()->setLocale($locale);

        // Log the change for debugging
        \Log::info("Language changed to: {$locale}", [
            'session_locale' => session('locale'),
            'cookie_locale' => request()->cookie('locale'),
            'app_locale' => app()->getLocale(),
            'user_id' => auth()->id()
        ]);

        // Clear any cached views to ensure new language takes effect
        if (app()->environment('local')) {
            \Artisan::call('view:clear');
        }
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Language changed to ' . ($locale == 'en' ? 'English' : 'Hiligaynon'));
})->name('language.switch');

// Test route to check current locale
Route::get('test-locale', function () {
    return response()->json([
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'cookie_locale' => request()->cookie('locale'),
        'english_test' => __('common.english'),
        'hiligaynon_test' => __('common.hiligaynon'),
        'categories_test' => __('common.categories')
    ]);
});

Route::get('/', [WelcomeController::class, 'index']);

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
Route::delete('forums/reply/{id}', [ForumsController::class, 'deleteReply'])->name('forums.reply.delete')->middleware('auth');

// Test route for video functionality (remove in production)
Route::get('forums/test-video', [ForumsController::class, 'testVideo'])->name('forums.test-video');

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

    // Farmer Orders Routes
    Route::get('/farmer/orders', [FarmerOrderController::class, 'index'])->name('farmer.orders.index');
    Route::get('/farmer/orders/{order}', [FarmerOrderController::class, 'show'])->name('farmer.orders.show');
    Route::patch('/farmer/orders/{order}/status', [FarmerOrderController::class, 'updateStatus'])->name('farmer.orders.updateStatus');
});

//buyer Dashboard Route
Route::middleware(['auth', 'buyer'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');

    // Buyer Orders Routes
    Route::get('/buyer/orders', [BuyerOrderController::class, 'index'])->name('buyer.orders');
    Route::get('/buyer/orders/{order}', [BuyerOrderController::class, 'show'])->name('buyer.orders.show');

    // Buyer Wishlist Routes
    Route::get('/buyer/wishlist', [BuyerWishlistController::class, 'index'])->name('buyer.wishlist');
    Route::post('/buyer/wishlist/add', [BuyerWishlistController::class, 'add'])->name('buyer.wishlist.add');
    Route::delete('/buyer/wishlist/remove/{id}', [BuyerWishlistController::class, 'remove'])->name('buyer.wishlist.remove');
    Route::delete('/buyer/wishlist/remove-product/{productId}', [BuyerWishlistController::class, 'removeByProduct'])->name('buyer.wishlist.removeByProduct');
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

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

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
