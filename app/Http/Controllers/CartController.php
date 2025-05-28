<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_per_unit,
                    'quantity' => $details['quantity'],
                    'unit' => $product->unit_type,
                    'image' => $product->image_url ? asset('storage/' . $product->image_url) : 'https://via.placeholder.com/300x200?text=No+Image',
                    'subtotal' => $product->price_per_unit * $details['quantity']
                ];
                $total += $product->price_per_unit * $details['quantity'];
            }
        }

        return view('shop.cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$request->product_id])) {
            $newQuantity = $cart[$request->product_id]['quantity'] + $request->quantity;
            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cart[$request->product_id]['quantity'] = $newQuantity;
        } else {
            $cart[$request->product_id] = [
                'quantity' => $request->quantity
            ];
        }

        Session::put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$request->product_id]);
        } else {
            if ($request->quantity > $product->stock_quantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cart[$request->product_id]['quantity'] = $request->quantity;
        }

        Session::put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }
}