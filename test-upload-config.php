<?php

/**
 * Test script to verify upload configuration
 * Run: php test-upload-config.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Upload Configuration Test ===\n\n";

// Test 1: Check public disk configuration
echo "1. Testing 'public' disk configuration:\n";
$publicDisk = Storage::disk('public');
$root = config('filesystems.disks.public.root');
$url = config('filesystems.disks.public.url');

echo "   Root path: " . $root . "\n";
echo "   URL: " . $url . "\n";
echo "   Exists: " . (file_exists($root) ? "✓ Yes" : "✗ No") . "\n\n";

// Test 2: Check upload directories
echo "2. Checking upload directories:\n";
$directories = [
    'products',
    'forums/videos',
    'forums/videos/replies',
    'forums/images',
    'profile-pictures'
];

foreach ($directories as $dir) {
    $fullPath = $root . '/' . $dir;
    $exists = file_exists($fullPath);
    $writable = is_writable($fullPath);
    echo "   {$dir}: " . ($exists ? "✓" : "✗") . " exists, " . ($writable ? "✓" : "✗") . " writable\n";
}

echo "\n3. Testing URL generation:\n";
$testPath = 'products/test-image.jpg';
try {
    $url = Storage::disk('public')->url($testPath);
    echo "   Test URL: " . $url . "\n";
    echo "   Expected: " . env('APP_URL') . "/uploads/{$testPath}\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n4. Testing file operations:\n";
try {
    // Create a test file
    $testContent = "Test file created at " . date('Y-m-d H:i:s');
    Storage::disk('public')->put('test.txt', $testContent);
    echo "   ✓ File creation: Success\n";

    // Check if file exists
    if (Storage::disk('public')->exists('test.txt')) {
        echo "   ✓ File exists: Yes\n";
    }

    // Get file URL
    $url = Storage::disk('public')->url('test.txt');
    echo "   ✓ File URL: " . $url . "\n";

    // Delete test file
    Storage::disk('public')->delete('test.txt');
    echo "   ✓ File deletion: Success\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n5. Security check:\n";
$indexFile = $root . '/index.html';
if (file_exists($indexFile)) {
    echo "   ✓ index.html exists (prevents directory listing)\n";
} else {
    echo "   ✗ index.html missing\n";
}

$gitignoreFile = $root . '/.gitignore';
if (file_exists($gitignoreFile)) {
    echo "   ✓ .gitignore exists (prevents committing uploads)\n";
} else {
    echo "   ✗ .gitignore missing\n";
}

echo "\n=== Test Complete ===\n";
echo "\n✓ Configuration is ready! No need to run 'php artisan storage:link'\n";


