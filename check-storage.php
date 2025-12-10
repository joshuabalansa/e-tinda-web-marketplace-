<?php
/**
 * Quick Storage Diagnostic Script
 * Run this via: php check-storage.php
 * Or access via web: https://your-domain.com/check-storage.php (place in public folder)
 */

// If running from command line, bootstrap Laravel
if (php_sapi_name() === 'cli') {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

echo "=== STORAGE DIAGNOSTIC CHECK ===\n\n";

// Check 1: File existence
$filename = 'SWJWd2YxvPJTc9FhoncuuoeopcHHdvQA867RhzQd.jpg';
$fullPath = storage_path('app/public/products/' . $filename);
echo "1. FILE EXISTENCE CHECK\n";
echo "   Path: $fullPath\n";
echo "   Exists: " . (file_exists($fullPath) ? "YES ✓" : "NO ✗") . "\n";
if (file_exists($fullPath)) {
    echo "   Size: " . filesize($fullPath) . " bytes\n";
    echo "   Readable: " . (is_readable($fullPath) ? "YES ✓" : "NO ✗") . "\n";
    echo "   Permissions: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "\n";
}
echo "\n";

// Check 2: Storage directory
$storageDir = storage_path('app/public/products');
echo "2. STORAGE DIRECTORY CHECK\n";
echo "   Path: $storageDir\n";
echo "   Exists: " . (file_exists($storageDir) ? "YES ✓" : "NO ✗") . "\n";
echo "   Writable: " . (is_writable($storageDir) ? "YES ✓" : "NO ✗") . "\n";
if (file_exists($storageDir)) {
    $files = glob($storageDir . '/*');
    echo "   Files count: " . count($files) . "\n";
    echo "   First 5 files:\n";
    foreach (array_slice($files, 0, 5) as $file) {
        echo "      - " . basename($file) . "\n";
    }
}
echo "\n";

// Check 3: Route registration
echo "3. ROUTE REGISTRATION CHECK\n";
try {
    $routeExists = Route::has('storage.file');
    echo "   Storage route registered: " . ($routeExists ? "YES ✓" : "NO ✗") . "\n";

    // List all routes matching 'storage'
    $routes = Route::getRoutes();
    echo "   Routes with 'storage':\n";
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'storage')) {
            echo "      - " . $route->methods()[0] . " " . $route->uri() . " (name: " . $route->getName() . ")\n";
        }
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// Check 4: Configuration
echo "4. CONFIGURATION CHECK\n";
echo "   APP_URL: " . config('app.url') . "\n";
echo "   FILESYSTEM_DISK: " . config('filesystems.default') . "\n";
echo "   Public disk driver: " . config('filesystems.disks.public.driver') . "\n";
echo "   Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "   Public disk URL: " . config('filesystems.disks.public.url') . "\n";
echo "\n";

// Check 5: Storage facade test
echo "5. STORAGE FACADE TEST\n";
try {
    $disk = Storage::disk('public');
    $testPath = 'products/' . $filename;
    echo "   Can access Storage facade: YES ✓\n";
    echo "   File exists via Storage: " . ($disk->exists($testPath) ? "YES ✓" : "NO ✗") . "\n";
    if ($disk->exists($testPath)) {
        echo "   File size via Storage: " . $disk->size($testPath) . " bytes\n";
        echo "   Generated URL: " . $disk->url($testPath) . "\n";
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// Check 6: Symlink
echo "6. SYMLINK CHECK\n";
$symlinkPath = public_path('storage');
echo "   Symlink path: $symlinkPath\n";
echo "   Exists: " . (file_exists($symlinkPath) ? "YES" : "NO") . "\n";
echo "   Is link: " . (is_link($symlinkPath) ? "YES" : "NO") . "\n";
if (is_link($symlinkPath)) {
    $target = readlink($symlinkPath);
    echo "   Target: $target\n";
    echo "   Target exists: " . (file_exists($target) ? "YES ✓" : "NO ✗") . "\n";
}
echo "\n";

// Check 7: .htaccess
echo "7. .HTACCESS CHECK\n";
$htaccessPath = public_path('.htaccess');
echo "   Path: $htaccessPath\n";
echo "   Exists: " . (file_exists($htaccessPath) ? "YES ✓" : "NO ✗") . "\n";
echo "\n";

// Check 8: Cache status
echo "8. CACHE STATUS\n";
$routeCachePath = base_path('bootstrap/cache/routes-v7.php');
$configCachePath = base_path('bootstrap/cache/config.php');
echo "   Routes cached: " . (file_exists($routeCachePath) ? "YES (clear with: php artisan route:clear)" : "NO") . "\n";
echo "   Config cached: " . (file_exists($configCachePath) ? "YES (clear with: php artisan config:clear)" : "NO") . "\n";
echo "\n";

// Check 9: Test product from database
echo "9. DATABASE CHECK\n";
try {
    $product = \App\Models\Product::whereNotNull('image_url')
        ->where('image_url', 'like', '%' . $filename . '%')
        ->first();

    if ($product) {
        echo "   Found product with this image: YES ✓\n";
        echo "   Product ID: " . $product->id . "\n";
        echo "   Product name: " . $product->name . "\n";
        echo "   Stored image_url: " . $product->image_url . "\n";
        echo "   Generated URL: " . $product->getImageUrl() . "\n";
    } else {
        echo "   Found product with this image: NO\n";
        echo "   (Checking first product with any image...)\n";
        $product = \App\Models\Product::whereNotNull('image_url')->first();
        if ($product) {
            echo "   Sample product ID: " . $product->id . "\n";
            echo "   Sample image_url: " . $product->image_url . "\n";
            echo "   Sample generated URL: " . $product->getImageUrl() . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== DIAGNOSIS ===\n";
if (file_exists($fullPath)) {
    echo "✓ File EXISTS on server\n";
    echo "✗ But URL returns 404 - This means:\n";
    echo "   - Route might be cached incorrectly\n";
    echo "   - Web server might be handling request before Laravel\n";
    echo "   - Route might not be registered properly\n\n";
    echo "IMMEDIATE FIX:\n";
    echo "1. Run: php artisan route:clear\n";
    echo "2. Run: php artisan config:clear\n";
    echo "3. Run: php artisan cache:clear\n";
    echo "4. DON'T run route:cache yet - test first\n";
    echo "5. Try accessing the image again\n";
} else {
    echo "✗ File DOES NOT EXIST on server\n";
    echo "   - File was never uploaded or was deleted\n";
    echo "   - Check upload functionality\n";
    echo "   - Check if files are being saved to correct location\n";
}

echo "\n=== END ===\n";


