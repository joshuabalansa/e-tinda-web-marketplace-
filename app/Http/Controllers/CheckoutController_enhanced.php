// Enhanced CheckoutController method for multi-vendor handling
public function process(Request $request)
{
    // ... existing validation ...

    $cart = Session::get('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    try {
        DB::beginTransaction();

        // Group cart items by farmer
        $farmerGroups = [];
        foreach ($cart as $id => $details) {
            $product = Product::with('user')->findOrFail($id);
            $farmerId = $product->user_id;

            if (!isset($farmerGroups[$farmerId])) {
                $farmerGroups[$farmerId] = [
                    'farmer' => $product->user,
                    'items' => [],
                    'subtotal' => 0
                ];
            }

            $farmerGroups[$farmerId]['items'][$id] = $details;
            $farmerGroups[$farmerId]['subtotal'] += $product->price_per_unit * $details['quantity'];
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($farmerGroups as $group) {
            $subtotal += $group['subtotal'];
        }

        $shipping = $request->shipping;
        $total = $subtotal + $shipping;
        $isMultiVendor = count($farmerGroups) > 1;

        // Create main order
        $order = Order::create([
            'user_id' => auth()->id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address ?? 'Farm Pickup',
            'city' => $request->city ?? 'Farm Location',
            'state' => $request->state ?? 'Farm Province',
            'zip' => $request->zip ?? '0000',
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'delivery_option' => $request->delivery_option,
            'payment_method' => $request->payment_method,
            'special_instructions' => $request->special_instructions,
            'is_multi_vendor' => $isMultiVendor,
            'delivery_notes' => $isMultiVendor ? 'Multi-vendor order - see individual farmer sections for pickup details' : null
        ]);

        // Create order items with farmer-specific information
        foreach ($cart as $id => $details) {
            $product = Product::findOrFail($id);

            if ($product->stock_quantity < $details['quantity']) {
                throw new \Exception("Not enough stock available for {$product->name}");
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $details['quantity'],
                'price' => $product->price_per_unit,
                'farmer_delivery_status' => 'pending',
                'farmer_notes' => $isMultiVendor ? "Part of multi-vendor order #{$order->id}" : null
            ]);

            // Update product stock
            $product->stock_quantity -= $details['quantity'];
            $product->save();
        }

        // Clear cart
        Session::forget('cart');

        DB::commit();

        return redirect()->route('checkout.success', ['orderId' => $order->id])
                       ->with('success', $isMultiVendor ?
                           'Multi-vendor order placed successfully! You will receive pickup details for each farmer.' :
                           'Order placed successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage());
    }
}
