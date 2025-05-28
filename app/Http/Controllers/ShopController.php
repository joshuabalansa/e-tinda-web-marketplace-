<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('user')
            ->where('status', 'available');

        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Price range filter
        if ($request->has('min_price')) {
            $query->where('price_per_unit', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price_per_unit', '<=', $request->max_price);
        }

        // Sort products
        switch ($request->sort) {
            case 'price_low_high':
                $query->orderBy('price_per_unit', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price_per_unit', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(9)
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

        // Get unique categories for filter
        $categories = Product::where('status', 'available')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('shop.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('user')->findOrFail($id);

        if ($product->status !== 'available') {
            abort(404);
        }

        $productData = [
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

        // Get related products (same category)
        $relatedProducts = Product::with('user')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->take(4)
            ->get()
            ->map(function ($relatedProduct) {
                return [
                    'id' => $relatedProduct->id,
                    'name' => $relatedProduct->name,
                    'price' => $relatedProduct->price_per_unit,
                    'unit' => $relatedProduct->unit_type,
                    'image' => $relatedProduct->image_url ? asset('storage/' . $relatedProduct->image_url) : 'https://via.placeholder.com/300x200?text=No+Image',
                ];
            });

        return view('shop.product', compact('productData', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::with('user')
            ->where('status', 'available')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
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

        return view('shop.index', compact('products'));
    }

    /**
     * Get products by category
     */
    public function category($category)
    {
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

        return view('shop.index', compact('products'));
    }
}
