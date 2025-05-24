<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Get cart items from session or use dummy data if empty
        $cartItems = session('cart', [
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
        ]);

        // Store cart items in session if they're not already there
        if (!session()->has('cart')) {
            session(['cart' => $cartItems]);
        }

        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = 5.00;
        $total = $subtotal + $shipping;

        return view('shop.carts', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function addToCart(Request $request)
    {
        $cartItems = session('cart', []);

        // Add new item to cart
        $cartItems[] = [
            'id' => $request->product_id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity ?? 1,
            'image' => $request->image,
            'unit' => $request->unit
        ];

        session(['cart' => $cartItems]);

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    public function removeFromCart($id)
    {
        $cartItems = session('cart', []);

        // Remove item from cart
        $cartItems = array_filter($cartItems, function($item) use ($id) {
            return $item['id'] != $id;
        });

        session(['cart' => $cartItems]);

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function updateQuantity(Request $request, $id)
    {
        $cartItems = session('cart', []);

        // Update item quantity
        foreach ($cartItems as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] = $request->quantity;
                break;
            }
        }

        session(['cart' => $cartItems]);

        return response()->json(['success' => true]);
    }
}