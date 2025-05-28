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
            'shipping_address' => 'required|string',
            'contact_number' => 'required|string',
            'payment_method' => 'required|in:cod,gcash'
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address' => $request->shipping_address,
                'contact_number' => $request->contact_number,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total_amount' => 0 // Will be updated after adding items
            ]);

            $total = 0;

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
                    'price' => $product->price_per_unit,
                    'subtotal' => $product->price_per_unit * $details['quantity']
                ]);

                // Update product stock
                $product->stock_quantity -= $details['quantity'];
                $product->save();

                $total += $product->price_per_unit * $details['quantity'];
            }

            // Update order total
            $order->total_amount = $total;
            $order->save();

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
