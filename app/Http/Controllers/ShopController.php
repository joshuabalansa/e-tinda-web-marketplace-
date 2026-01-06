<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with(['user', 'user.association'])
                ->where('status', 'available')
                ->whereHas('user'); // Only show products with valid users

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

            // Cooperative filter
            if ($request->filled('cooperative') && $request->cooperative !== 'all') {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('association_id', $request->cooperative)
                      ->where('role', 'farmer');
                });
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

            // Paginate the results
            $products = $query->paginate(9);

            // Transform the items without losing pagination
            $products->getCollection()->transform(function ($product) {
                try {
                    $cooperative = null;
                    if ($product->user && $product->user->association) {
                        $cooperative = [
                            'id' => $product->user->association->association_id,
                            'name' => $product->user->association->name,
                            'slug' => $product->user->association->slug,
                        ];
                    }

                    return (object)[
                        'id' => $product->id,
                        'name' => $product->name ?? 'Unnamed Product',
                        'price' => $product->price_per_unit ?? 0,
                        'unit' => $product->unit_type ?? 'piece',
                        'image' => $product->getImageUrl() ?? asset('assets/images/placeholder.jpg'),
                        'description' => $product->description ?? '',
                        'vendor' => $product->user ? $product->user->name : 'Unknown Vendor',
                        'location' => $product->user ? $product->user->getFormattedLocation() : 'Location not available',
                        'category' => $product->category ?? 'Other',
                        'stock' => $product->stock_quantity ?? 0,
                        'harvest_date' => $product->harvest_date ? $product->harvest_date->format('Y-m-d') : 'N/A',
                        'storage' => 'Store in a cool, dry place',
                        'cooperative' => $cooperative
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing product in shop index', [
                        'product_id' => $product->id ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            });

            // Filter out null entries from the collection
            $products->setCollection($products->getCollection()->filter());

            // Get unique categories for filter
            $categories = Product::where('status', 'available')
                ->whereHas('user')
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->sort()
                ->values();

            // Get active cooperatives for filter (safe query - handles missing columns)
            try {
                // Check if association_id column exists in users table
                $hasAssociationIdColumn = Schema::hasColumn('users', 'association_id');

                if ($hasAssociationIdColumn) {
                    // Check if is_active column exists before using it
                    $hasIsActiveColumn = Schema::hasColumn('associations', 'is_active');
                    if ($hasIsActiveColumn) {
                        $cooperatives = \App\Models\Association::where('is_active', true)
                            ->whereHas('farmers')
                            ->orderBy('name', 'asc')
                            ->get();
                    } else {
                        // Fallback: get all associations if is_active column doesn't exist yet
                        $cooperatives = \App\Models\Association::whereHas('farmers')
                            ->orderBy('name', 'asc')
                            ->get();
                    }
                } else {
                    // If association_id doesn't exist, return empty collection
                    $cooperatives = collect([]);
                }
            } catch (\Exception $e) {
                // If query fails for any reason, return empty collection
                \Log::warning('Error loading cooperatives in ShopController', [
                    'error' => $e->getMessage()
                ]);
                $cooperatives = collect([]);
            }

            // If AJAX request, return JSON
            if ($request->ajax()) {
                $hasFilters = request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock', 'cooperative']);
                $activeFiltersHtml = '';

                if ($hasFilters) {
                    try {
                        $activeFiltersHtml = view('shop.partials.active-filters')->render();
                    } catch (\Exception $e) {
                        \Log::error('Error rendering active filters', ['error' => $e->getMessage()]);
                    }
                }

                return response()->json([
                    'products_html' => view('shop.partials.products', compact('products'))->render(),
                    'pagination_html' => $products->hasPages() ? $products->appends(request()->query())->links('pagination::bootstrap-4')->toHtml() : '',
                    'active_filters_html' => $activeFiltersHtml,
                    'results_count' => $products->total(),
                    'has_filters' => $hasFilters
                ]);
            }

            return view('shop.index', compact('products', 'categories', 'cooperatives'));
        } catch (\Exception $e) {
            \Log::error('Error in ShopController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return empty results instead of crashing
            $emptyCollection = collect([]);
            $products = new LengthAwarePaginator(
                $emptyCollection,
                0,
                9,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $categories = collect([]);
            $cooperatives = collect([]);

            return view('shop.index', compact('products', 'categories', 'cooperatives'))
                ->with('error', 'Unable to load products. Please try again later.');
        }
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

        // Get farmer coordinates if available
        $farmerCoordinates = null;
        $farmer = $product->user;
        if ($farmer && $farmer->hasValidCoordinates()) {
            $farmerCoordinates = $farmer->getCoordinatesArray();
        }

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price_per_unit,
            'unit' => $product->unit_type,
            'image' => $product->getImageUrl(),
            'description' => $product->description,
            'vendor' => $product->user ? $product->user->name : 'Unknown Vendor',
            'location' => $product->user ? $product->user->getFormattedLocation() : 'Location not available',
            'category' => $product->category,
            'stock' => $product->stock_quantity,
            'harvest_date' => $product->harvest_date ? $product->harvest_date->format('Y-m-d') : 'N/A',
            'storage' => 'Store in a cool, dry place',
            'average_rating' => round($product->averageRating(), 1),
            'total_reviews' => $product->totalReviews()
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
                    'image' => $relatedProduct->getImageUrl() ?? asset('assets/images/placeholder.jpg'),
                ];
            });

        return view('shop.product', compact('productData', 'relatedProducts', 'isInWishlist', 'farmerCoordinates', 'farmer'));
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
                'vendor' => $product->user ? $product->user->name : 'Unknown Vendor',
                'location' => $product->user ? $product->user->getFormattedLocation() : 'Location not available',
                'category' => $product->category,
                'stock' => $product->stock_quantity,
                'harvest_date' => $product->harvest_date ? $product->harvest_date->format('Y-m-d') : 'N/A',
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
                'vendor' => $product->user ? $product->user->name : 'Unknown Vendor',
                'location' => $product->user ? $product->user->getFormattedLocation() : 'Location not available',
                'category' => $product->category,
                    'stock' => $product->stock_quantity,
                    'harvest_date' => $product->harvest_date ? $product->harvest_date->format('Y-m-d') : 'N/A',
                    'storage' => 'Store in a cool, dry place'
                ];
            });

        return view('shop.index', compact('products'));
    }

    /**
     * Get reviews for a product
     */
    public function getReviews($id)
    {
        $product = Product::findOrFail($id);

        $reviews = Review::with(['buyer'])
            ->where('product_id', $id)
            ->orderBy('review_date', 'desc')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->review_id,
                    'buyer_name' => $review->buyer->name ?? 'Anonymous',
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'review_date' => $review->review_date->format('M d, Y'),
                ];
            });

        $averageRating = $product->averageRating();
        $totalReviews = $product->totalReviews();

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
        ]);
    }
}
