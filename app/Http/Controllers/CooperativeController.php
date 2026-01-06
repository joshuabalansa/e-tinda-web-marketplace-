<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class CooperativeController extends Controller
{
    /**
     * Display a listing of all cooperatives/storefronts
     */
    public function index(Request $request)
    {
        try {
            $query = Association::withCount(['farmers' => function($q) {
                $q->where('role', 'farmer');
            }])
            ->where('is_active', true);

            // Search filter
            if ($request->filled('query')) {
                $searchTerm = trim($request->input('query'));
                if (!empty($searchTerm)) {
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%")
                          ->orWhere('description', 'like', "%{$searchTerm}%")
                          ->orWhere('location', 'like', "%{$searchTerm}%");
                    });
                }
            }

            // Featured filter
            if ($request->boolean('featured')) {
                $query->where('featured', true);
            }

            // Sort
            $sort = $request->input('sort', 'name');
            $direction = $request->input('direction', 'asc');

            if (in_array($sort, ['name', 'date_established', 'total_members'])) {
                $query->orderBy($sort, $direction);
            } else {
                $query->orderBy('name', 'asc');
            }

            $cooperatives = $query->paginate(12);

            return view('cooperatives.index', compact('cooperatives'));
        } catch (\Exception $e) {
            Log::error('Error in CooperativeController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $emptyCollection = collect([]);
            $cooperatives = new LengthAwarePaginator(
                $emptyCollection,
                0,
                12,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            return view('cooperatives.index', compact('cooperatives'))
                ->with('error', 'Unable to load cooperatives. Please try again later.');
        }
    }

    /**
     * Display the specified cooperative storefront
     */
    public function show(Request $request, $id)
    {
        try {
            // Try to find by slug first, then by ID
            $cooperative = Association::where(function($query) use ($id) {
                $query->where('slug', $id)
                      ->orWhere('association_id', $id);
            })
            ->where('is_active', true)
            ->firstOrFail();

            // Get products from farmers in this cooperative
            $productsQuery = Product::with('user')
                ->whereHas('user', function($query) use ($cooperative) {
                    $query->where('association_id', $cooperative->association_id)
                          ->where('role', 'farmer');
                })
                ->where('status', 'available');

            // Search filter
            if ($request->filled('query')) {
                $searchTerm = trim($request->input('query'));
                if (!empty($searchTerm)) {
                    $productsQuery->where(function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%")
                          ->orWhere('description', 'like', "%{$searchTerm}%")
                          ->orWhere('category', 'like', "%{$searchTerm}%");
                    });
                }
            }

            // Category filter
            if ($request->filled('category') && $request->category !== 'all') {
                $productsQuery->where('category', $request->category);
            }

            // Price range filter
            if ($request->filled('min_price') && is_numeric($request->min_price) && $request->min_price >= 0) {
                $productsQuery->where('price_per_unit', '>=', (float)$request->min_price);
            }
            if ($request->filled('max_price') && is_numeric($request->max_price) && $request->max_price >= 0) {
                $productsQuery->where('price_per_unit', '<=', (float)$request->max_price);
            }

            // Stock filter
            if ($request->boolean('in_stock')) {
                $productsQuery->where('stock_quantity', '>', 0);
            }

            // Sort products
            $allowedSorts = ['price_low_high', 'price_high_low', 'newest', 'name_asc', 'name_desc'];
            $sort = $request->input('sort');

            if (in_array($sort, $allowedSorts)) {
                switch ($sort) {
                    case 'price_low_high':
                        $productsQuery->orderBy('price_per_unit', 'asc');
                        break;
                    case 'price_high_low':
                        $productsQuery->orderBy('price_per_unit', 'desc');
                        break;
                    case 'newest':
                        $productsQuery->latest();
                        break;
                    case 'name_asc':
                        $productsQuery->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $productsQuery->orderBy('name', 'desc');
                        break;
                }
            } else {
                $productsQuery->latest();
            }

            // Paginate the results
            $products = $productsQuery->paginate(9);

            // Transform the items without losing pagination
            $products->getCollection()->transform(function ($product) {
                try {
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
                        'storage' => 'Store in a cool, dry place'
                    ];
                } catch (\Exception $e) {
                    Log::error('Error processing product in cooperative show', [
                        'product_id' => $product->id ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            });

            // Filter out null entries from the collection
            $products->setCollection($products->getCollection()->filter());

            // Get unique categories for filter
            $categories = Product::whereHas('user', function($query) use ($cooperative) {
                $query->where('association_id', $cooperative->association_id)
                      ->where('role', 'farmer');
            })
            ->where('status', 'available')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

            // Get farmer count
            $farmerCount = $cooperative->farmers()->count();

            // If AJAX request, return JSON
            if ($request->ajax()) {
                $hasFilters = request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']);
                $activeFiltersHtml = '';

                if ($hasFilters) {
                    try {
                        $activeFiltersHtml = view('shop.partials.active-filters')->render();
                    } catch (\Exception $e) {
                        Log::error('Error rendering active filters', ['error' => $e->getMessage()]);
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

            return view('cooperatives.show', compact('cooperative', 'products', 'categories', 'farmerCount'));
        } catch (\Exception $e) {
            Log::error('Error in CooperativeController@show', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'Cooperative not found');
        }
    }
}

