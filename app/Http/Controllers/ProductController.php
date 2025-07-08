<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->latest()->paginate(10);
        return view('farmer.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('farmer.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|string|in:kg,g,lb,piece,dozen',
            'stock_quantity' => 'required|integer|min:0',
            'harvest_date' => 'required|date',
            'category' => 'required|string|in:Vegetables,Fruits,Grains,Dairy,Meat,Other',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:available,unavailable,out_of_stock'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('farmer.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('farmer.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => '',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|string|in:kg,g,lb,piece,dozen',
            'stock_quantity' => 'required|integer|min:0',
            'harvest_date' => 'required|date',
            'category' => 'required|string|in:Vegetables,Fruits,Grains,Dairy,Meat,Other',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:available,unavailable,out_of_stock'
        ]);

        $data = $request->all();

        if ($request->hasFile('image_url')) {
            // Delete old image
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $imagePath = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
