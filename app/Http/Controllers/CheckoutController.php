<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;

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

        return view('shop.checkout', compact('items', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'shipping' => 'required|numeric|min:0'
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate subtotal from cart
            $subtotal = 0;
            foreach ($cart as $id => $details) {
                $product = Product::findOrFail($id);
                $subtotal += $product->price_per_unit * $details['quantity'];
            }

            // Calculate total with shipping
            $shipping = $request->shipping;
            $total = $subtotal + $shipping;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending'
            ]);

            // Add order items
            foreach ($cart as $id => $details) {
                $product = Product::findOrFail($id);

                if ($product->stock_quantity < $details['quantity']) {
                    throw new \Exception("Not enough stock available for {$product->name}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'price' => $product->price_per_unit
                ]);

                // Update product stock
                $product->stock_quantity -= $details['quantity'];
                $product->save();
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('checkout.success', ['orderId' => $order->id])
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['items.product', 'user'])
                     ->where('user_id', auth()->id())
                     ->findOrFail($orderId);

        return view('shop.checkout-success', compact('order'));
    }
}
