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
use App\Http\Controllers\AdminReportsController;
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
use App\Http\Controllers\CartController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;

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

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switchLanguage'])->name('language.switch');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Categories routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Buyer routes
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
    Route::get('/buyer/wishlist', [BuyerWishlistController::class, 'index'])->name('buyer.wishlist');
    Route::post('/buyer/wishlist/add', [BuyerWishlistController::class, 'add'])->name('buyer.wishlist.add');
    Route::post('/buyer/wishlist/{product}', [BuyerWishlistController::class, 'store'])->name('buyer.wishlist.store');
    Route::delete('/buyer/wishlist/{product}', [BuyerWishlistController::class, 'destroy'])->name('buyer.wishlist.destroy');
    Route::delete('/buyer/wishlist/remove-product/{productId}', [BuyerWishlistController::class, 'removeByProduct'])->name('buyer.wishlist.remove-product');
    Route::delete('/buyer/wishlist/remove/{id}', [BuyerWishlistController::class, 'remove'])->name('buyer.wishlist.remove');
    Route::get('/buyer/orders', [BuyerDashboardController::class, 'orders'])->name('buyer.orders');
    Route::get('/buyer/orders/{order}', [BuyerDashboardController::class, 'showOrder'])->name('buyer.orders.show');
    Route::post('/buyer/orders/{order}/cancel', [BuyerDashboardController::class, 'cancelOrder'])->name('buyer.orders.cancel');
    Route::get('/buyer/history', [BuyerDashboardController::class, 'history'])->name('buyer.history');
});

// Checkout routes - Allow both buyers and farmers to checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.store');
    Route::post('/checkout/calculate-delivery-fee', [CheckoutController::class, 'calculateDeliveryFee'])->name('checkout.calculate-delivery-fee');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Forums routes
Route::get('/forums', [ForumsController::class, 'index'])->name('forums.index');
Route::get('/forums/create', [ForumsController::class, 'create'])->name('forums.create')->middleware('auth');
Route::post('/forums', [ForumsController::class, 'store'])->name('forums.store')->middleware('auth');
Route::get('/forums/{forum}', [ForumsController::class, 'show'])->name('forums.show');
Route::get('/forums/topic/{forum}', [ForumsController::class, 'show'])->name('forums.topic');
Route::get('/forums/{forum}/edit', [ForumsController::class, 'edit'])->name('forums.edit')->middleware('auth');
Route::put('/forums/{forum}', [ForumsController::class, 'update'])->name('forums.update')->middleware('auth');
Route::delete('/forums/{forum}', [ForumsController::class, 'destroy'])->name('forums.destroy')->middleware('auth');
Route::post('/forums/{forum}/reply', [ForumsController::class, 'storeReply'])->name('forums.reply')->middleware('auth');
Route::post('/forums/replies/{reply}/helpful', [ForumsController::class, 'markHelpful'])->name('forums.helpful')->middleware('auth');
Route::delete('/forums/replies/{reply}', [ForumsController::class, 'deleteReply'])->name('forums.reply.delete')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Admin settings
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

    // User management routes
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');

    // Analytics and reports
    Route::get('/admin/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('/admin/analytics/data', [AdminAnalyticsController::class, 'getData'])->name('admin.analytics.data');
    Route::get('/admin/reports', [AdminReportsController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/export', [AdminReportsController::class, 'export'])->name('admin.reports.export');

    // Forum management
    Route::get('/admin/forums', [AdminForumController::class, 'index'])->name('admin.forums.index');
    Route::get('/admin/forums/{forum}', [AdminForumController::class, 'show'])->name('admin.forums.show');
    Route::post('/admin/forums/{forum}/update-status', [AdminForumController::class, 'updateStatus'])->name('admin.forums.update-status');
    Route::post('/admin/forums/{forum}/toggle-flag', [AdminForumController::class, 'toggleFlag'])->name('admin.forums.toggle-flag');
    Route::delete('/admin/forums/{forum}', [AdminForumController::class, 'destroy'])->name('admin.forums.destroy');
    Route::post('/admin/forums/{forum}/reply', [AdminForumController::class, 'reply'])->name('admin.forums.reply');
    Route::post('/admin/forums/bulk-action', [AdminForumController::class, 'bulkAction'])->name('admin.forums.bulk-action');

    // Forum replies management
    Route::get('/admin/forums/replies', [AdminForumController::class, 'replies'])->name('admin.forums.replies.index');
    Route::post('/admin/forums/replies/{reply}/update-status', [AdminForumController::class, 'updateReplyStatus'])->name('admin.forums.replies.update-status');
    Route::post('/admin/forums/replies/{reply}/toggle-flag', [AdminForumController::class, 'toggleReplyFlag'])->name('admin.forums.replies.toggle-flag');
    Route::delete('/admin/forums/replies/{reply}', [AdminForumController::class, 'destroyReply'])->name('admin.forums.replies.destroy');
    Route::post('/admin/forums/replies/bulk-action', [AdminForumController::class, 'bulkReplyAction'])->name('admin.forums.replies.bulk-action');
});

// Farmer routes
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer', [FarmerDashboardController::class, 'index'])->name('farmer.dashboard');
    Route::get('/farmer/products', [FarmerProductsController::class, 'index'])->name('farmer.products.index');
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
    Route::get('/farmer/reports', [FarmerReportsController::class, 'index'])->name('farmer.reports.index');
    Route::get('/farmer/reports/export', [FarmerReportsController::class, 'export'])->name('farmer.reports.export');
    Route::get('/farmer/forums', [FarmerForumsController::class, 'index'])->name('farmer.forums');
    Route::get('/farmer/forums/{forum}', [FarmerForumsController::class, 'show'])->name('farmer.forums.show');
    Route::post('/farmer/forums/{forum}/reply', [FarmerForumsController::class, 'reply'])->name('farmer.forums.reply');
    Route::get('/farmer/account', [FarmerAccountController::class, 'index'])->name('farmer.account');
    Route::post('/farmer/account', [FarmerAccountController::class, 'update'])->name('farmer.account.update');
    Route::get('/farmer/account/export', [FarmerAccountController::class, 'exportData'])->name('farmer.account.export-data');
});

// Health check route for Railway
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});