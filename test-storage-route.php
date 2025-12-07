<?php
/**
 * Quick test script to verify storage route works
 * Run: php test-storage-route.php
 */

echo "Testing Storage Route Configuration\n";
echo "====================================\n\n";

// Check if storage directory exists
$storagePath = __DIR__ . '/storage/app/public/products';
echo "1. Checking storage directory...\n";
if (is_dir($storagePath)) {
    echo "   ✓ Directory exists: $storagePath\n";
    
    // List files
    $files = scandir($storagePath);
    $imageFiles = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']);
    });
    
    if (count($imageFiles) > 0) {
        echo "   ✓ Found " . count($imageFiles) . " file(s):\n";
        foreach ($imageFiles as $file) {
            $size = filesize($storagePath . '/' . $file);
            echo "     - $file (" . number_format($size / 1024, 2) . " KB)\n";
        }
    } else {
        echo "   ⚠ No files found in products directory\n";
    }
} else {
    echo "   ✗ Directory does not exist: $storagePath\n";
    echo "   Run: mkdir -p $storagePath\n";
}

echo "\n2. Checking routes file...\n";
$routesFile = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesFile);

if (strpos($routesContent, "Route::get('/storage/{path}'") !== false) {
    echo "   ✓ Storage route found in routes/web.php\n";
} else {
    echo "   ✗ Storage route NOT found in routes/web.php\n";
    echo "   The route needs to be added!\n";
}

echo "\n3. Checking .env configuration...\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Check FILESYSTEM_DISK
    if (preg_match('/FILESYSTEM_DISK=(.+)/', $envContent, $matches)) {
        $disk = trim($matches[1]);
        if ($disk === 'public') {
            echo "   ✓ FILESYSTEM_DISK=public\n";
        } else {
            echo "   ⚠ FILESYSTEM_DISK=$disk (should be 'public')\n";
        }
    } else {
        echo "   ⚠ FILESYSTEM_DISK not set in .env\n";
    }
    
    // Check APP_URL
    if (preg_match('/APP_URL=(.+)/', $envContent, $matches)) {
        $url = trim($matches[1]);
        echo "   ✓ APP_URL=$url\n";
        
        if (strpos($url, 'https://') === 0) {
            echo "   ✓ Using HTTPS\n";
        } else {
            echo "   ⚠ Not using HTTPS (production should use HTTPS)\n";
        }
    } else {
        echo "   ⚠ APP_URL not set in .env\n";
    }
} else {
    echo "   ⚠ .env file not found (using .env.example as reference)\n";
}

echo "\n4. Checking Product model...\n";
$productModel = __DIR__ . '/app/Models/Product.php';
$productContent = file_get_contents($productModel);

if (strpos($productContent, 'Storage::disk(\'public\')->url') !== false) {
    echo "   ✓ Product model uses Storage::disk('public')->url()\n";
} else {
    echo "   ⚠ Product model might not be using Storage::url()\n";
}

echo "\n5. Testing file access simulation...\n";
if (is_dir($storagePath)) {
    $testFile = $storagePath . '/test-' . time() . '.txt';
    if (file_put_contents($testFile, 'Test content')) {
        echo "   ✓ Can write to storage directory\n";
        
        if (is_readable($testFile)) {
            echo "   ✓ Can read from storage directory\n";
        } else {
            echo "   ✗ Cannot read from storage directory\n";
        }
        
        unlink($testFile);
    } else {
        echo "   ✗ Cannot write to storage directory\n";
        echo "   Run: chmod -R 775 storage\n";
    }
}

echo "\n====================================\n";
echo "Test Complete!\n\n";

echo "Next Steps:\n";
echo "1. Commit changes: git add . && git commit -m 'Fix storage route'\n";
echo "2. Push to repository: git push\n";
echo "3. Deploy to Laravel Cloud\n";
echo "4. Test image URL: https://your-domain.com/storage/products/your-image.jpg\n";
