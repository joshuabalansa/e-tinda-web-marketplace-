<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get cart items from database (if authenticated) or session (if guest)
     */
    private function getCartItems()
    {
        if (Auth::check()) {
            // Load from database for authenticated users
            $cartItems = Auth::user()->cartItems()->with('product')->get();
            $items = [];

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                if ($product) {
                    $items[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price_per_unit,
                        'quantity' => $cartItem->quantity,
                        'unit' => $product->unit_type,
                        'image' => $product->getImageUrl(),
                        'subtotal' => $product->price_per_unit * $cartItem->quantity
                    ];
                }
            }

            return $items;
        } else {
            // Load from session for guests
            $cart = Session::get('cart', []);
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
                        'image' => $product->getImageUrl(),
                        'subtotal' => $product->price_per_unit * $details['quantity']
                    ];
                }
            }

            return $items;
        }
    }

    public function index()
    {
        $items = $this->getCartItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item['subtotal'];
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

        if (Auth::check()) {
            // Save to database for authenticated users
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);

            $newQuantity = $cartItem->exists ? $cartItem->quantity + $request->quantity : $request->quantity;

            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Not enough stock available.');
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Save to session for guests
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
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($request->product_id);

        if (Auth::check()) {
            // Update in database for authenticated users
            if ($request->quantity == 0) {
                CartItem::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->delete();
            } else {
                if ($request->quantity > $product->stock_quantity) {
                    return back()->with('error', 'Not enough stock available.');
                }

                $cartItem = CartItem::firstOrNew([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id
                ]);
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            }
        } else {
            // Update in session for guests
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
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        if (Auth::check()) {
            // Remove from database for authenticated users
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->delete();
        } else {
            // Remove from session for guests
            $cart = Session::get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                Session::put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        if (Auth::check()) {
            // Clear from database for authenticated users
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            // Clear from session for guests
            Session::forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    /**
     * Sync session cart with database cart when user logs in
     * This method is called from AuthenticatedSessionController
     */
    public function syncSessionToDatabase($userId)
    {
        $sessionCart = Session::get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $details) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            $cartItem = CartItem::firstOrNew([
                'user_id' => $userId,
                'product_id' => $productId
            ]);

            // If item already exists in database, add session quantity to it
            // Otherwise, use session quantity
            if ($cartItem->exists) {
                $newQuantity = $cartItem->quantity + $details['quantity'];
                // Don't exceed stock
                $cartItem->quantity = min($newQuantity, $product->stock_quantity);
            } else {
                $cartItem->quantity = min($details['quantity'], $product->stock_quantity);
            }

            $cartItem->save();
        }

        // Clear session cart after syncing
        Session::forget('cart');
    }
}