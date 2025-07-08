<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        // Get real categories from products in the database
        $categoryData = Product::where('status', 'available')
            ->select('category')
            ->selectRaw('COUNT(*) as product_count')
            ->groupBy('category')
            ->get();

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

            return [
                'name' => $item->category,
                'description' => $categoryDescriptions[$item->category] ?? 'Quality farm products',
                'image' => $categoryImages[$item->category] ?? 'https://via.placeholder.com/300x200?text=Category',
                'product_count' => $item->product_count
            ];
        });

        return view('shop.categories.index', compact('categories'));
    }

    public function show($category)
    {
        // Get products for the specific category
        $products = Product::with('user')
            ->where('status', 'available')
            ->where('category', $category)
            ->latest()
            ->paginate(9)
            ->through(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_per_unit,
                    'unit' => $product->unit_type,
                    'image' => $product->image_url ? asset('storage/' . $product->image_url) : 'https://via.placeholder.com/300x200?text=No+Image',
                    'description' => $product->description,
                    'vendor' => $product->user->name,
                    'location' => $product->user->address ?? 'Location not specified',
                    'category' => $product->category,
                    'stock' => $product->stock_quantity,
                    'harvest_date' => $product->harvest_date->format('Y-m-d'),
                    'storage' => 'Store in a cool, dry place'
                ];
            });

        // Get unique categories for filter (for the shop layout)
        $categories = Product::where('status', 'available')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('shop.index', compact('products', 'categories'))->with('currentCategory', $category);
    }
}