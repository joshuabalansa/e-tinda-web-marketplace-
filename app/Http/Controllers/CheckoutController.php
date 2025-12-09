<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Services\DeliveryFeeCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Get formatted farmer location for checkout display
     * Returns a concise format: city, state, country
     */
    private function getFarmerLocationForCheckout($farmer)
    {
        $parts = [];

        if ($farmer->city) {
            $parts[] = $farmer->city;
        }

        if ($farmer->state) {
            $parts[] = $farmer->state;
        }

        if ($farmer->country) {
            $parts[] = $farmer->country;
        }

        if (!empty($parts)) {
            return implode(', ', $parts);
        }

        return 'Philippines';
    }

    /**
     * Get cart items from database (if authenticated) or session (if guest)
     */
    private function getCartItems()
    {
        if (Auth::check()) {
            // Load from database for authenticated users
            $cartItems = Auth::user()->cartItems()->with('product.user')->get();
            $items = [];

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                if ($product) {
                    $farmer = $product->user;
                    $imageUrl = $product->getImageUrl();

                    $items[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price_per_unit,
                        'quantity' => $cartItem->quantity,
                        'unit' => $product->unit_type ?? 'unit',
                        'image' => $imageUrl,
                        'subtotal' => $product->price_per_unit * $cartItem->quantity,
                        'farmer_name' => $farmer->business_name ?? $farmer->name ?? 'Local Farmer',
                        'farmer_location' => $this->getFarmerLocationForCheckout($farmer),
                        'farmer_id' => $farmer->id
                    ];
                }
            }

            return $items;
        } else {
            // Load from session for guests
            $cart = Session::get('cart', []);
            $items = [];

            foreach ($cart as $id => $details) {
                $product = Product::with('user')->find($id);
                if ($product) {
                    $farmer = $product->user;
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
                        'farmer_location' => $this->getFarmerLocationForCheckout($farmer),
                        'farmer_id' => $farmer->id
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
        $shippingCost = 100; // Default shipping cost

        foreach ($items as $item) {
            $total += $item['subtotal'];
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

        // Get cart items
        $items = $this->getCartItems();
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate subtotal from cart items
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['subtotal'];
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
            foreach ($items as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock available for {$product->name}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price_per_unit
                ]);

                // Update product stock
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            // Clear cart
            if (Auth::check()) {
                // Clear from database for authenticated users
                CartItem::where('user_id', Auth::id())->delete();
            } else {
                // Clear from session for guests
                Session::forget('cart');
            }

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
