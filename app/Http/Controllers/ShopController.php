<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data for products
        $products = [
            [
                'id' => 1,
                'name' => 'Fresh Tomatoes',
                'price' => 3.99,
                'unit' => 'lb',
                'image' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80',
                'rating' => 4.5,
                'review_count' => 42,
                'description' => 'Juicy, vine-ripened tomatoes grown locally without pesticides.',
                'vendor' => 'Green Harvest Farms',
                'location' => 'Local Farm, Philippines',
                'certification' => 'Organic Certified',
                'category' => 'Vegetables',
                'stock' => 100,
                'harvest_date' => '2024-03-15',
                'storage' => 'Store in a cool, dry place'
            ],
            [
                'id' => 2,
                'name' => 'Organic Carrots',
                'price' => 2.49,
                'unit' => 'lb',
                'image' => 'https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80',
                'rating' => 4.0,
                'review_count' => 36,
                'description' => 'Sweet and crunchy carrots, perfect for snacking or cooking.',
                'vendor' => 'Fresh Valley Produce',
                'location' => 'Mountain Valley, Philippines',
                'certification' => 'Organic Certified',
                'category' => 'Vegetables',
                'stock' => 150,
                'harvest_date' => '2024-03-10',
                'storage' => 'Refrigerate for longer shelf life'
            ]
        ];

        return view('shop.index', compact('products'));
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
        // Dummy data for a single product
        $product = [
            'id' => $id,
            'name' => 'Fresh Tomatoes',
            'price' => 3.99,
            'unit' => 'lb',
            'image' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80',
            'rating' => 4.5,
            'review_count' => 42,
            'description' => 'Juicy, vine-ripened tomatoes grown locally without pesticides. Our tomatoes are carefully harvested at peak ripeness to ensure the best flavor and nutritional value. Perfect for salads, cooking, or eating fresh.',
            'vendor' => 'Green Harvest Farms',
            'location' => 'Local Farm, Philippines',
            'certification' => 'Organic Certified',
            'category' => 'Vegetables',
            'stock' => 100,
            'harvest_date' => '2024-03-15',
            'storage' => 'Store in a cool, dry place'
        ];

        return view('shop.product', compact('product'));
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
}
