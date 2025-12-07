<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\DeliveryFeeCalculator;
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
        $shippingCost = 100; // Default shipping cost

        foreach ($cart as $id => $details) {
            $product = Product::with('user')->find($id);
            if ($product) {
                $farmer = $product->user;
                // Get product image URL with fallback
                $imageUrl = $product->getImageUrl();

                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_per_unit,
                    'quantity' => $details['quantity'],
                    'unit' => $product->unit_type ?? 'unit',
                    'image' => $imageUrl,
                    'subtotal' => $product->price_per_unit * $details['quantity'],
                    'farmer_name' => $farmer->business_name ?? $farmer->name ?? 'Local Farmer',
                    'farmer_location' => ($farmer->city && $farmer->state)
                        ? $farmer->city . ', ' . $farmer->state
                        : ($farmer->city ?? 'Philippines'),
                    'farmer_id' => $farmer->id
                ];
                $total += $product->price_per_unit * $details['quantity'];
            }
        }

        return view('shop.checkout', compact('items', 'total', 'shippingCost'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'shipping' => 'required|numeric|min:0',
            'delivery_option' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:cash',
            'special_instructions' => 'nullable|string|max:2000'
        ]);

        // Additional validation for delivery option
        if ($request->delivery_option === 'delivery') {
            $request->validate([
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'zip' => 'required|string|max:20',
            ]);
        }

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

            // Create order with all user inputs
            $order = Order::create([
                'user_id' => auth()->id(),
                'first_name' => trim($request->first_name),
                'last_name' => trim($request->last_name),
                'email' => trim($request->email),
                'phone' => trim($request->phone),
                'address' => $request->delivery_option === 'delivery'
                    ? trim($request->address)
                    : 'Farm Pickup',
                'city' => $request->delivery_option === 'delivery'
                    ? trim($request->city)
                    : 'Farm Location',
                'state' => $request->delivery_option === 'delivery'
                    ? trim($request->state)
                    : 'Farm Province',
                'zip' => $request->delivery_option === 'delivery'
                    ? trim($request->zip)
                    : '0000',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'delivery_option' => $request->delivery_option,
                'payment_method' => $request->payment_method,
                'special_instructions' => $request->filled('special_instructions')
                    ? trim($request->special_instructions)
                    : null
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

            return redirect()->route('checkout.success', $order->id)
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Calculate delivery fee based on address
     */
    public function calculateDeliveryFee(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'farmer_location' => 'nullable|string'
        ]);

        $calculator = new DeliveryFeeCalculator();

        $result = $calculator->calculateDeliveryFee(
            $request->address,
            $request->city,
            $request->province,
            $request->farmer_location
        );

        return response()->json([
            'success' => true,
            'delivery_fee' => $result['delivery_fee'],
            'distance' => $result['distance'],
            'method' => $result['method'],
            'estimated_delivery_time' => $result['estimated_delivery_time']
        ]);
    }

    /**
     * Show success page after order completion
     */
    public function success($orderId)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);

        return view('checkout.success', compact('order'));
    }
}
