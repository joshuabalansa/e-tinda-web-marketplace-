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
    $products = [
        [
            'id' => 1,
            'name' => 'Product 1',
            'description' => 'This is product 1 description',
            'price' => 99.99,
            'image' => 'product1.jpg'
        ],
        [
            'id' => 2,
            'name' => 'Product 2',
            'description' => 'This is product 2 description',
            'price' => 149.99,
            'image' => 'product2.jpg'
        ],
        [
            'id' => 3,
            'name' => 'Product 3',
            'description' => 'This is product 3 description',
            'price' => 199.99,
            'image' => 'product3.jpg'
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
    public function show(string $id)
    {
        //
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
