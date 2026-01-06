<?php
/**
 * PWA Icon Generator
 * Generates all required PWA icons from favicon.ico
 *
 * Usage: php generate-pwa-icons.php
 */

$iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];
$outputDir = __DIR__ . '/public/icons';
$faviconPath = __DIR__ . '/public/favicon.ico';

// Create icons directory if it doesn't exist
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0755, true);
    echo "Created icons directory: $outputDir\n";
}

// Check if GD library is available
if (!extension_loaded('gd')) {
    echo "ERROR: GD library is not available. Please install php-gd extension.\n";
    echo "Alternatively, you can:\n";
    echo "1. Use online tools: https://www.pwabuilder.com/imageGenerator\n";
    echo "2. Install ImageMagick and use the convert command\n";
    echo "3. Manually create icons and place them in $outputDir\n";
    exit(1);
}

// Check if favicon exists
if (!file_exists($faviconPath)) {
    echo "ERROR: favicon.ico not found at $faviconPath\n";
    exit(1);
}

// Try to read favicon
$faviconData = file_get_contents($faviconPath);
if ($faviconData === false) {
    echo "ERROR: Could not read favicon.ico\n";
    exit(1);
}

// Try to create image from favicon
$sourceImage = @imagecreatefromstring($faviconData);
if ($sourceImage === false) {
    // If favicon is ICO format, try to create a simple colored icon
    echo "Note: Creating simple colored icons (favicon.ico format not directly supported by GD)\n";
    $sourceImage = imagecreatetruecolor(512, 512);
    $green = imagecolorallocate($sourceImage, 40, 167, 69); // #28a745
    $white = imagecolorallocate($sourceImage, 255, 255, 255);
    imagefill($sourceImage, 0, 0, $green);

    // Draw a simple leaf icon (E-Tinda logo)
    $centerX = 256;
    $centerY = 256;
    $radius = 200;

    // Draw a circle
    imagefilledellipse($sourceImage, $centerX, $centerY, $radius * 2, $radius * 2, $white);

    // Draw leaf shape (simplified)
    $points = [
        $centerX, $centerY - 100,
        $centerX + 80, $centerY - 40,
        $centerX + 60, $centerY + 40,
        $centerX, $centerY + 80,
        $centerX - 60, $centerY + 40,
        $centerX - 80, $centerY - 40,
    ];
    imagefilledpolygon($sourceImage, $points, 6, $green);
}

// Get original dimensions
$sourceWidth = imagesx($sourceImage);
$sourceHeight = imagesy($sourceImage);

echo "Generating PWA icons...\n";

foreach ($iconSizes as $size) {
    // Create new image with desired size
    $icon = imagecreatetruecolor($size, $size);

    // Enable alpha blending for transparency
    imagealphablending($icon, false);
    imagesavealpha($icon, true);

    // Fill with transparent background
    $transparent = imagecolorallocatealpha($icon, 0, 0, 0, 127);
    imagefill($icon, 0, 0, $transparent);

    // Resize and copy source image
    imagecopyresampled(
        $icon,
        $sourceImage,
        0, 0, 0, 0,
        $size, $size,
        $sourceWidth, $sourceHeight
    );

    // Save icon
    $outputPath = "$outputDir/icon-{$size}x{$size}.png";
    if (imagepng($icon, $outputPath, 9)) {
        echo "✓ Created: icon-{$size}x{$size}.png\n";
    } else {
        echo "✗ Failed: icon-{$size}x{$size}.png\n";
    }

    imagedestroy($icon);
}

imagedestroy($sourceImage);

echo "\nDone! Icons generated in: $outputDir\n";
echo "You can now test PWA installation.\n";


