<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            // Get all available products with categories
            // Using a simpler approach that works across all databases
            $products = Product::where('status', 'available')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->get(['category']);

            // Group by category in PHP for better compatibility
            $categoryData = $products->filter(function ($product) {
                return !empty(trim($product->category));
            })->groupBy(function ($product) {
                return trim($product->category);
            })->map(function ($items, $category) {
                return (object) [
                    'category' => $category,
                    'product_count' => $items->count()
                ];
            })->values();

            // Log for debugging if no categories found
            if ($categoryData->isEmpty()) {
                $totalProducts = Product::count();
                $availableProducts = Product::where('status', 'available')->count();
                $productsWithCategory = Product::where('status', 'available')
                    ->whereNotNull('category')
                    ->where('category', '!=', '')
                    ->count();
                \Log::warning('CategoryController: No categories found.', [
                    'total_products' => $totalProducts,
                    'available_products' => $availableProducts,
                    'products_with_category' => $productsWithCategory
                ]);
            }
        } catch (\Exception $e) {
            // Fallback to empty collection if database connection fails
            \Log::error('Database connection failed in CategoryController: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $categoryData = collect();
        }

        // Map categories to include images and descriptions
        $categories = $categoryData->map(function ($item) {
            $categoryImages = [
                'Vegetables' => 'https://images.unsplash.com/photo-1597362925123-77861d3fbac7?q=80&w=2940&auto=format&fit=crop',
                'Fruits' => 'https://images.unsplash.com/photo-1592558480962-903b06077406?q=80&w=2062&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'Dairy' => 'https://images.unsplash.com/photo-1628088062854-d1870b4553da?q=80&w=2940&auto=format&fit=crop',
                'Grains' => 'https://images.unsplash.com/photo-1606495002933-354ea2413e94?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'Meat' => 'https://images.unsplash.com/photo-1588347818481-c5c1d6b0e2c1?q=80&w=2940&auto=format&fit=crop',
                'Other' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=2940&auto=format&fit=crop'
            ];

            $categoryDescriptions = [
                'Vegetables' => 'Fresh, locally grown vegetables',
                'Fruits' => 'Seasonal and exotic fruits',
                'Dairy' => 'Fresh dairy from local farms',
                'Grains' => 'Organic grains and cereals',
                'Meat' => 'Fresh, locally sourced meat',
                'Other' => 'Various other farm products'
            ];

            // Normalize category name (trim and handle case)
            $categoryName = trim($item->category);

            return [
                'name' => $categoryName,
                'description' => $categoryDescriptions[$categoryName] ?? 'Quality farm products',
                'image' => $categoryImages[$categoryName] ?? 'https://via.placeholder.com/300x200?text=Category',
                'product_count' => (int) $item->product_count
            ];
        })->filter(function ($category) {
            // Filter out any invalid categories
            return !empty($category['name']);
        })->values();

        return view('shop.categories.index', compact('categories'));
    }

    public function show($category)
    {
        try {
            // Decode URL-encoded category name
            $category = urldecode($category);

            // Get products for the specific category (case-insensitive search)
            $products = Product::with('user')
                ->where('status', 'available')
                ->whereRaw('LOWER(TRIM(category)) = LOWER(?)', [trim($category)])
                ->latest()
                ->paginate(9);
                
            // Transform the items without losing pagination
            $products->getCollection()->transform(function ($product) {
                return (object)[
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_per_unit,
                    'unit' => $product->unit_type,
                    'image' => $product->getImageUrl(),
                    'description' => $product->description,
                    'vendor' => $product->user->name ?? 'Unknown Vendor',
                    'location' => $product->user->address ?? 'Location not specified',
                    'category' => $product->category,
                    'stock' => $product->stock_quantity,
                    'harvest_date' => $product->harvest_date ? $product->harvest_date->format('Y-m-d') : null,
                    'storage' => 'Store in a cool, dry place'
                ];
            });

            // Get unique categories for filter (for the shop layout)
            $categories = Product::where('status', 'available')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->whereRaw('TRIM(category) != ?', [''])
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->map(function ($cat) {
                    return trim($cat);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();

            // Log if no products found for debugging
            if ($products->isEmpty()) {
                \Log::warning('CategoryController show: No products found for category: ' . $category, [
                    'available_categories' => $categories->toArray()
                ]);
            }
        } catch (\Exception $e) {
            // Fallback to empty collections if database connection fails
            \Log::error('Database connection failed in CategoryController show method: ' . $e->getMessage(), [
                'category' => $category,
                'trace' => $e->getTraceAsString()
            ]);
            $products = collect();
            $categories = collect();
        }

        return view('shop.index', compact('products', 'categories'))->with('currentCategory', $category);
    }
}