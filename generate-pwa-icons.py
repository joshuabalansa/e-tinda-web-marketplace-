#!/usr/bin/env python3
"""
PWA Icon Generator
Generates all required PWA icons
"""

import os
import sys

try:
    from PIL import Image, ImageDraw, ImageFont
    PIL_AVAILABLE = True
except ImportError:
    PIL_AVAILABLE = False

icon_sizes = [72, 96, 128, 144, 152, 192, 384, 512]
output_dir = os.path.join(os.path.dirname(__file__), 'public', 'icons')
favicon_path = os.path.join(os.path.dirname(__file__), 'public', 'favicon.ico')

# Create icons directory
os.makedirs(output_dir, exist_ok=True)
print(f"Icons directory: {output_dir}")

if not PIL_AVAILABLE:
    print("ERROR: PIL/Pillow is not installed.")
    print("Install it with: pip3 install Pillow")
    print("\nAlternatively:")
    print("1. Use online tools: https://www.pwabuilder.com/imageGenerator")
    print("2. Install ImageMagick: sudo apt-get install imagemagick")
    print("3. Manually create icons and place them in:", output_dir)
    sys.exit(1)

# Try to load favicon
source_image = None
if os.path.exists(favicon_path):
    try:
        source_image = Image.open(favicon_path)
        print(f"Loaded favicon from: {favicon_path}")
    except Exception as e:
        print(f"Could not load favicon: {e}")
        source_image = None

# If no source image, create a simple one
if source_image is None:
    print("Creating simple E-Tinda icon...")
    # Create a 512x512 base image
    source_image = Image.new('RGBA', (512, 512), (40, 167, 69, 255))  # #28a745 green
    draw = ImageDraw.Draw(source_image)

    # Draw a white circle
    center = (256, 256)
    radius = 200
    draw.ellipse(
        [center[0] - radius, center[1] - radius, center[0] + radius, center[1] + radius],
        fill=(255, 255, 255, 255)
    )

    # Draw a simple leaf shape (E-Tinda logo)
    leaf_points = [
        (256, 156),  # top
        (336, 216),  # right
        (316, 296),  # right bottom
        (256, 336),  # bottom
        (196, 296),  # left bottom
        (176, 216),  # left
    ]
    draw.polygon(leaf_points, fill=(40, 167, 69, 255))

    # Add text "E" in the center (optional)
    try:
        # Try to use a font, fallback to default if not available
        font_size = 200
        try:
            font = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf", font_size)
        except:
            font = ImageFont.load_default()
        # Draw "E" text
        text = "E"
        bbox = draw.textbbox((0, 0), text, font=font)
        text_width = bbox[2] - bbox[0]
        text_height = bbox[3] - bbox[1]
        position = ((512 - text_width) // 2, (512 - text_height) // 2 - 20)
        draw.text(position, text, fill=(255, 255, 255, 255), font=font)
    except Exception as e:
        print(f"Could not add text: {e}")

# Convert to RGBA if needed
if source_image.mode != 'RGBA':
    source_image = source_image.convert('RGBA')

print("\nGenerating PWA icons...")

for size in icon_sizes:
    # Resize image
    icon = source_image.resize((size, size), Image.Resampling.LANCZOS)

    # Save icon
    output_path = os.path.join(output_dir, f"icon-{size}x{size}.png")
    icon.save(output_path, 'PNG', optimize=True)
    print(f"✓ Created: icon-{size}x{size}.png")

print(f"\nDone! Icons generated in: {output_dir}")
print("You can now test PWA installation.")


