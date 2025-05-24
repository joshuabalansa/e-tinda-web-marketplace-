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
        $request->validate([
            'product_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|url',
            'unit' => 'required',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $cartItems = session('cart', []);

        // Check if product already exists in cart
        $existingItemIndex = collect($cartItems)->search(function($item) use ($request) {
            return $item['id'] == $request->product_id;
        });

        if ($existingItemIndex !== false) {
            // Update quantity if product exists
            $cartItems[$existingItemIndex]['quantity'] += $request->quantity ?? 1;
            $message = "Updated quantity of {$request->name} in your cart!";
        } else {
            // Add new item to cart
            $cartItems[] = [
                'id' => $request->product_id,
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity ?? 1,
                'image' => $request->image,
                'unit' => $request->unit
            ];
            $message = "{$request->name} has been added to your cart!";
        }

        session(['cart' => $cartItems]);

        return redirect()->back()->with('success', $message);
    }

    public function removeFromCart($id)
    {
        $cartItems = session('cart', []);

        // Get item name before removing
        $itemName = collect($cartItems)->firstWhere('id', $id)['name'] ?? 'Item';

        // Remove item from cart
        $cartItems = array_filter($cartItems, function($item) use ($id) {
            return $item['id'] != $id;
        });

        session(['cart' => $cartItems]);

        return redirect()->back()->with('success', "{$itemName} has been removed from your cart!");
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

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!'
        ]);
    }
}