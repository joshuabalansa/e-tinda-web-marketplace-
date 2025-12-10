<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FarmerProductsController extends Controller
{
    /**
     * Display a listing of the farmer's products.
     */
    public function index()
    {
        $user = Auth::user();
        $products = $user->products()->latest()->paginate(10);

        return view('farmer.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('farmer.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'harvest_date' => 'nullable|date',
            'category' => 'required|string|in:Vegetables,Fruits,Grains,Dairy,Meat,Other',
            'status' => 'required|string|in:available,unavailable,out_of_stock',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $product = new Product();
        $product->user_id = Auth::id();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price_per_unit = $request->price_per_unit;
        $product->unit_type = $request->unit_type;
        $product->stock_quantity = $request->stock_quantity;
        $product->harvest_date = $request->harvest_date;

        // Handle image upload
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('products', 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Ensure the product belongs to the authenticated farmer
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        return view('farmer.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Ensure the product belongs to the authenticated farmer
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        return view('farmer.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Ensure the product belongs to the authenticated farmer
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'harvest_date' => 'nullable|date',
            'category' => 'required|string|in:Vegetables,Fruits,Grains,Dairy,Meat,Other',
            'status' => 'required|string|in:available,unavailable,out_of_stock',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price_per_unit = $request->price_per_unit;
        $product->unit_type = $request->unit_type;
        $product->stock_quantity = $request->stock_quantity;
        $product->harvest_date = $request->harvest_date;

        // Handle image upload
        if ($request->hasFile('image_url')) {
            // Delete old image if exists
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            $imagePath = $request->file('image_url')->store('products', 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Ensure the product belongs to the authenticated farmer
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        // Delete image if exists
        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Get reviews for a product
     */
    public function getReviews(Product $product)
    {
        // Ensure the product belongs to the authenticated farmer
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        $reviews = Review::with(['buyer'])
            ->where('product_id', $product->id)
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
