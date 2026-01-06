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
            'special_instructions' => 'nullable|string|max:2000',
            'is_negotiation' => 'nullable|boolean',
            'negotiated_prices' => 'nullable|array',
            'negotiated_prices.*' => 'nullable|numeric|min:0',
            'negotiation_notes' => 'nullable|string|max:1000'
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

        // Check if this is a negotiation request
        $negotiatedPrices = $request->negotiated_prices ?? [];

        // Filter out empty values from negotiated prices (including empty strings, null, 0, etc.)
        $filteredNegotiatedPrices = array_filter($negotiatedPrices, function($price) {
            return $price !== null
                && $price !== ''
                && trim($price) !== ''
                && floatval($price) > 0;
        });

        $hasNegotiatedPrices = !empty($filteredNegotiatedPrices);

        // Set is_negotiation if explicitly set OR if negotiated prices are provided
        $isNegotiation = $request->input('is_negotiation') == '1'
            || $request->input('is_negotiation') === 1
            || $request->input('is_negotiation') === true
            || $hasNegotiatedPrices;

        // Debug logging with all request data
        \Log::info('Checkout Negotiation Debug', [
            'is_negotiation_input' => $request->input('is_negotiation'),
            'is_negotiation_parsed' => $isNegotiation,
            'negotiated_prices_raw' => $negotiatedPrices,
            'negotiated_prices_filtered' => $filteredNegotiatedPrices,
            'has_negotiated_prices' => $hasNegotiatedPrices,
            'cart_items' => array_map(function($item) {
                return ['id' => $item['id'], 'name' => $item['name'] ?? 'unknown'];
            }, $items)
        ]);

        try {
            DB::beginTransaction();

            // Calculate subtotal - use negotiated prices if provided
            $subtotal = 0;
            foreach ($items as $item) {
                $productId = $item['id'];
                $negotiatedPriceForSubtotal = null;

                // Check for negotiated price using multiple key formats
                $productIdKeys = [
                    $productId,
                    (string)$productId,
                    (int)$productId
                ];

                foreach ($productIdKeys as $key) {
                    if (isset($negotiatedPrices[$key])
                        && $negotiatedPrices[$key] !== null
                        && $negotiatedPrices[$key] !== ''
                        && floatval($negotiatedPrices[$key]) > 0) {
                        $negotiatedPriceForSubtotal = floatval($negotiatedPrices[$key]);
                        break;
                    }
                }

                if ($negotiatedPriceForSubtotal !== null) {
                    // Use negotiated price
                    $subtotal += $negotiatedPriceForSubtotal * $item['quantity'];
                } else {
                    // Use original price
                    $subtotal += $item['subtotal'];
                }
            }

            // Calculate total with shipping
            $shipping = $request->shipping;
            $total = $subtotal + $shipping;

            // Ensure is_negotiation is set if we have negotiated prices
            // This handles cases where negotiated prices are provided but the flag wasn't explicitly set
            if ($hasNegotiatedPrices && !$isNegotiation) {
                $isNegotiation = true;
            }

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
                    : null,
                'is_negotiation' => $isNegotiation,
                'negotiation_notes' => $isNegotiation && $request->filled('negotiation_notes')
                    ? trim($request->negotiation_notes)
                    : null
            ]);

            // Track if we found any negotiated prices during item creation
            $foundNegotiatedPrices = false;

            // Add order items
            foreach ($items as $item) {
                $product = Product::findOrFail($item['id']);

                // Only check stock if NOT a negotiation (stock will be deducted when farmer accepts)
                if (!$isNegotiation) {
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Not enough stock available for {$product->name}");
                    }
                } else {
                    // For negotiations, just check if product exists and has stock
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Not enough stock available for {$product->name}. Please adjust your order.");
                    }
                }

                // Get negotiated price - check for it regardless of is_negotiation flag
                // This ensures prices are saved even if checkbox wasn't checked but prices were entered
                $negotiatedPrice = null;
                $productIdKeys = [
                    $product->id,
                    (string)$product->id,
                    (int)$product->id,
                    $item['id'],
                    (string)$item['id'],
                    (int)$item['id']
                ];

                foreach ($productIdKeys as $key) {
                    if (isset($negotiatedPrices[$key])
                        && $negotiatedPrices[$key] !== null
                        && $negotiatedPrices[$key] !== ''
                        && trim($negotiatedPrices[$key]) !== '') {
                        $priceValue = floatval($negotiatedPrices[$key]);
                        // Allow price to be > 0 and <= original price (or allow equal for flexibility)
                        if ($priceValue > 0 && $priceValue <= $product->price_per_unit) {
                            $negotiatedPrice = $priceValue;
                            $foundNegotiatedPrices = true;
                            // If we found a negotiated price, ensure is_negotiation is set
                            if (!$isNegotiation) {
                                $isNegotiation = true;
                            }
                            break;
                        }
                    }
                }

                // Log if we're in negotiation mode but didn't find a price for this item
                if ($isNegotiation && $negotiatedPrice === null && !empty($filteredNegotiatedPrices)) {
                    \Log::debug('Negotiation mode but no price found for item', [
                        'product_id' => $product->id,
                        'item_id' => $item['id'],
                        'available_keys' => array_keys($negotiatedPrices),
                        'tried_keys' => $productIdKeys
                    ]);
                }

                // Additional check: if is_negotiation is true but we didn't find a price, log it
                if ($isNegotiation && $negotiatedPrice === null) {
                    \Log::warning('Negotiation flag set but no negotiated price found', [
                        'order_id' => $order->id ?? 'not_created',
                        'product_id' => $product->id,
                        'item_id' => $item['id'] ?? null,
                        'negotiated_prices_keys' => array_keys($negotiatedPrices),
                        'negotiated_prices' => $negotiatedPrices
                    ]);
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price_per_unit,
                    'negotiated_price' => $negotiatedPrice
                ]);

                // Only update product stock if NOT a negotiation
                if (!$isNegotiation) {
                    $product->stock_quantity -= $item['quantity'];
                    $product->save();
                }
            }

            // Update order's is_negotiation flag if we found any negotiated prices
            // This ensures the flag is set even if it wasn't set during order creation
            if ($foundNegotiatedPrices) {
                if (!$order->is_negotiation) {
                    $order->update(['is_negotiation' => true]);
                    \Log::info('Updated order is_negotiation flag', [
                        'order_id' => $order->id,
                        'found_negotiated_prices' => $foundNegotiatedPrices
                    ]);
                }
            } else if ($isNegotiation && !$foundNegotiatedPrices) {
                // Log warning if negotiation was expected but no prices were found
                \Log::warning('Negotiation flag set but no negotiated prices found in items', [
                    'order_id' => $order->id,
                    'negotiated_prices_input' => $negotiatedPrices,
                    'filtered_prices' => $filteredNegotiatedPrices
                ]);
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

            if ($isNegotiation) {
                return redirect()->route('checkout.success', $order->id)
                               ->with('success', 'Price negotiation request submitted! The farmer will review your offer.');
            }

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
