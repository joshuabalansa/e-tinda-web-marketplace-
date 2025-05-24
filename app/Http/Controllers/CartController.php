<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Dummy cart data
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Fresh Tomatoes',
                'price' => 3.99,
                'quantity' => 2,
                'image' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80',
                'unit' => 'lb'
            ],
            [
                'id' => 2,
                'name' => 'Organic Carrots',
                'price' => 2.49,
                'quantity' => 1,
                'image' => 'https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80',
                'unit' => 'lb'
            ],
            [
                'id' => 3,
                'name' => 'Farm Fresh Eggs',
                'price' => 4.99,
                'quantity' => 1,
                'image' => 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80',
                'unit' => 'dozen'
            ]
        ];

        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = 5.00;
        $total = $subtotal + $shipping;

        return view('buyer.carts.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function addToCart(Request $request)
    {
        // For now, just redirect back with a success message
        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    public function removeFromCart($id)
    {
        // For now, just redirect back with a success message
        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function updateQuantity(Request $request, $id)
    {
        // For now, just redirect back with a success message
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
}