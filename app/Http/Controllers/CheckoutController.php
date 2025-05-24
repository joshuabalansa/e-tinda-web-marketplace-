<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Get cart items from session
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = 100; // You can make this dynamic based on your shipping rules
        $total = $subtotal + $shipping;

        return view('shop.checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
        ]);

        // Get cart items
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = 100; // You can make this dynamic based on your shipping rules
        $total = $subtotal + $shipping;

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip' => $validated['zip'],
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending'
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Clear the cart
            session()->forget('cart');

            // Redirect to success page
            return redirect()->route('checkout.success', $order->id)
                           ->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'There was an error processing your order. Please try again.')
                           ->withInput();
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('shop.checkout-success', compact('order'));
    }
}
