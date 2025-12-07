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

        // Search filter
        if ($request->filled('query')) {
            $searchTerm = trim($request->input('query'));
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('category', 'like', "%{$searchTerm}%");
                });
            }
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Price range filter
        if ($request->filled('min_price') && is_numeric($request->min_price) && $request->min_price >= 0) {
            $query->where('price_per_unit', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price') && is_numeric($request->max_price) && $request->max_price >= 0) {
            $query->where('price_per_unit', '<=', (float)$request->max_price);
        }

        // Stock filter
        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sort products
        $allowedSorts = ['price_low_high', 'price_high_low', 'newest', 'name_asc', 'name_desc'];
        $sort = $request->input('sort');

        if (in_array($sort, $allowedSorts)) {
            switch ($sort) {
                case 'price_low_high':
                    $query->orderBy('price_per_unit', 'asc');
                    break;
                case 'price_high_low':
                    $query->orderBy('price_per_unit', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(9)
            ->through(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_per_unit,
                    'unit' => $product->unit_type,
                    'image' => $product->getImageUrl(),
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
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        // If AJAX request, return JSON
        if ($request->ajax()) {
            $hasFilters = request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']);
            $activeFiltersHtml = '';

            if ($hasFilters) {
                $activeFiltersHtml = view('shop.partials.active-filters')->render();
            }

            return response()->json([
                'products_html' => view('shop.partials.products', compact('products'))->render(),
                'pagination_html' => $products->hasPages() ? $products->appends(request()->query())->links('pagination::bootstrap-4')->toHtml() : '',
                'active_filters_html' => $activeFiltersHtml,
                'results_count' => $products->total(),
                'has_filters' => $hasFilters
            ]);
        }

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

        // Check if product is in user's wishlist
        $isInWishlist = false;
        if (auth()->check()) {
            $isInWishlist = auth()->user()->wishlist()->where('product_id', $product->id)->exists();
        }

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price_per_unit,
            'unit' => $product->unit_type,
            'image' => $product->getImageUrl(),
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
                    'image' => $relatedProduct->getImageUrl(),
                ];
            });

        return view('shop.product', compact('productData', 'relatedProducts', 'isInWishlist'));
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
                    'image' => $product->getImageUrl(),
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
                    'image' => $product->getImageUrl(),
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
