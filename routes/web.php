<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\BuyerWishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ForumsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAnalyticsController;
use App\Http\Controllers\AdminForumController;
use App\Http\Controllers\FarmerDashboardController;
use App\Http\Controllers\FarmerProductsController;
use App\Http\Controllers\FarmerOrdersController;
use App\Http\Controllers\FarmerAnalyticsController;
use App\Http\Controllers\FarmerReportsController;
use App\Http\Controllers\FarmerForumsController;
use App\Http\Controllers\FarmerAccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\ProfileController;

// Database connection test route
Route::get('/db-test', function () {
    try {
        $pdo = new PDO(
            "mysql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            [
                PDO::ATTR_TIMEOUT => 10,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
        
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful',
            'test_query' => $result,
            'config' => [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'connection' => env('DB_CONNECTION'),
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage(),
            'config' => [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'connection' => env('DB_CONNECTION'),
            ]
        ], 500);
    }
});

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Shop routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/shop/product/{id}', [ShopController::class, 'show'])->name('shop.product.show');

// Categories routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Buyer routes
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
    Route::get('/buyer/wishlist', [BuyerWishlistController::class, 'index'])->name('buyer.wishlist');
    Route::post('/buyer/wishlist/{product}', [BuyerWishlistController::class, 'store'])->name('buyer.wishlist.store');
    Route::delete('/buyer/wishlist/{product}', [BuyerWishlistController::class, 'destroy'])->name('buyer.wishlist.destroy');
    Route::get('/buyer/orders', [BuyerDashboardController::class, 'orders'])->name('buyer.orders');
    Route::get('/buyer/history', [BuyerDashboardController::class, 'history'])->name('buyer.history');
});

// Checkout routes
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Forums routes
Route::get('/forums', [ForumsController::class, 'index'])->name('forums.index');
Route::get('/forums/{forum}', [ForumsController::class, 'show'])->name('forums.show');
Route::post('/forums/{forum}/reply', [ForumsController::class, 'reply'])->name('forums.reply')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{user}', [AdminController::class, 'userDetails'])->name('admin.users.show');
    Route::get('/admin/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/forums', [AdminForumController::class, 'index'])->name('admin.forums');
    Route::get('/admin/forums/{forum}', [AdminForumController::class, 'show'])->name('admin.forums.show');
    Route::post('/admin/forums/{forum}/reply', [AdminForumController::class, 'reply'])->name('admin.forums.reply');
});

// Farmer routes
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer', [FarmerDashboardController::class, 'index'])->name('farmer.dashboard');
    Route::get('/farmer/products', [FarmerProductsController::class, 'index'])->name('farmer.products');
    Route::get('/farmer/products/create', [FarmerProductsController::class, 'create'])->name('farmer.products.create');
    Route::post('/farmer/products', [FarmerProductsController::class, 'store'])->name('farmer.products.store');
    Route::get('/farmer/products/{product}', [FarmerProductsController::class, 'show'])->name('farmer.products.show');
    Route::get('/farmer/products/{product}/edit', [FarmerProductsController::class, 'edit'])->name('farmer.products.edit');
    Route::put('/farmer/products/{product}', [FarmerProductsController::class, 'update'])->name('farmer.products.update');
    Route::delete('/farmer/products/{product}', [FarmerProductsController::class, 'destroy'])->name('farmer.products.destroy');
    Route::get('/farmer/orders', [FarmerOrdersController::class, 'index'])->name('farmer.orders');
    Route::get('/farmer/orders/{order}', [FarmerOrdersController::class, 'show'])->name('farmer.orders.show');
    Route::put('/farmer/orders/{order}/status', [FarmerOrdersController::class, 'updateStatus'])->name('farmer.orders.update-status');
    Route::get('/farmer/analytics', [FarmerAnalyticsController::class, 'index'])->name('farmer.analytics');
    Route::get('/farmer/reports', [FarmerReportsController::class, 'index'])->name('farmer.reports');
    Route::get('/farmer/forums', [FarmerForumsController::class, 'index'])->name('farmer.forums');
    Route::get('/farmer/forums/{forum}', [FarmerForumsController::class, 'show'])->name('farmer.forums.show');
    Route::post('/farmer/forums/{forum}/reply', [FarmerForumsController::class, 'reply'])->name('farmer.forums.reply');
    Route::get('/farmer/account', [FarmerAccountController::class, 'index'])->name('farmer.account');
    Route::put('/farmer/account', [FarmerAccountController::class, 'update'])->name('farmer.account.update');
});

// Health check route for Railway
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});