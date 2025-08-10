<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get real categories from products in the database (limit to 3)
        $categoryData = Product::where('status', 'available')
            ->select('category')
            ->selectRaw('COUNT(*) as product_count')
            ->groupBy('category')
            ->orderBy('product_count', 'desc')
            ->limit(3)
            ->get();

        // Map categories to include images and descriptions
        $categories = $categoryData->map(function ($item) {
            $categoryImages = [
                'Vegetables' => 'https://images.unsplash.com/photo-1597362925123-77861d3fbac7?q=80&w=2940&auto=format&fit=crop',
                'Fruits' => 'https://images.unsplash.com/photo-1592558480962-903b06077406?q=80&w=2062&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'Dairy' => 'https://images.unsplash.com/photo-1633179963862-72c64dc6d30d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'Grains' => 'https://images.unsplash.com/photo-1606495002933-354ea2413e94?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'Meat' => 'https://images.unsplash.com/photo-1588347818481-c5c1d6b0e2c1?q=80&w=2940&auto=format&fit=crop',
                'Other' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=2940&auto=format&fit=crop'
            ];

            $categoryDescriptions = [
                'Vegetables' => 'Fresh, locally grown vegetables',
                'Fruits' => 'Seasonal and exotic fruits',
                'Dairy' => 'Farm-fresh dairy products',
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

        // Get featured products (limit to 4)
        $featuredProducts = Product::with('user')
            ->where('status', 'available')
            ->latest()
            ->limit(4)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_per_unit,
                    'unit' => $product->unit_type,
                    'image' => $product->image_url ? asset('storage/' . $product->image_url) : 'https://via.placeholder.com/300x200?text=No+Image',
                    'description' => $product->description,
                    'vendor' => $product->user->name,
                    'category' => $product->category,
                    'stock' => $product->stock_quantity
                ];
            });

        return view('welcome', compact('categories', 'featuredProducts'));
    }
}