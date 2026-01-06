<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FarmerOrdersController extends Controller
{
    /**
     * Display a listing of the farmer's orders.
     */
    public function index()
    {
        $user = Auth::user();

        // Get orders that contain products from this farmer
        $orders = Order::whereHas('items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['items.product', 'user'])->latest()->paginate(10);

        // Get order statistics
        $stats = $this->getOrderStats($user);

        return view('farmer.orders.index', compact('orders', 'stats'));
    }

    /**
     * Get order statistics for the farmer.
     */
    private function getOrderStats($user)
    {
        $orders = Order::whereHas('items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return [
            'total_orders' => $orders->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'processing_orders' => $orders->where('status', 'processing')->count(),
            'total_revenue' => $orders->sum('total_amount'),
        ];
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // Refresh the order to ensure we have the latest data
        $order->refresh();

        // Load all relationships - make sure items are loaded with all fields including negotiated_price
        $order->load(['items.product', 'items.product.user', 'user']);

        // Ensure the order contains products from this farmer
        // Load items with all fields to ensure negotiated_price is available
        // Explicitly select all fields including negotiated_price
        $farmerOrderItems = $order->items()
            ->select('order_items.*') // Explicitly select all order_items fields
            ->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('product')
            ->get();

        if ($farmerOrderItems->isEmpty()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Debug: Log negotiation status for troubleshooting
        if (config('app.debug')) {
            \Log::info('Farmer Order Show Debug', [
                'order_id' => $order->id,
                'is_negotiation' => $order->is_negotiation,
                'status' => $order->status,
                'farmer_items_count' => $farmerOrderItems->count(),
                'items_with_negotiated_price' => $farmerOrderItems->filter(function($item) {
                    return $item->negotiated_price !== null && $item->negotiated_price > 0;
                })->count(),
                'all_items_with_negotiated_price' => $order->items->filter(function($item) {
                    return $item->negotiated_price !== null && $item->negotiated_price > 0;
                })->count()
            ]);
        }

        return view('farmer.orders.show', compact('order', 'farmerOrderItems'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        // Ensure the order contains products from this farmer
        $farmerOrderItems = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        if ($farmerOrderItems->isEmpty()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Accept price negotiation for an order
     */
    public function acceptNegotiation(Request $request, Order $order)
    {
        $user = Auth::user();

        // Check if this order contains products from the authenticated farmer
        $farmerOrderItems = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('product')->get();

        if ($farmerOrderItems->isEmpty()) {
            abort(403, 'You can only accept negotiations for orders that contain your products.');
        }

        // Check if this is a negotiation order or has negotiated prices
        $hasNegotiatedPrices = $farmerOrderItems->contains(function($item) {
            return $item->negotiated_price !== null;
        });

        if (!$order->is_negotiation && !$hasNegotiatedPrices) {
            return redirect()->back()->with('error', 'This order is not a negotiation request.');
        }

        // Check if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'This negotiation has already been processed.');
        }

        try {
            DB::beginTransaction();

            // Update order items to use negotiated prices and deduct stock
            $newSubtotal = 0;
            foreach ($farmerOrderItems as $item) {
                if ($item->negotiated_price !== null) {
                    // Use negotiated price
                    $itemPrice = $item->negotiated_price;
                } else {
                    // Use original price if no negotiation was provided for this item
                    $itemPrice = $item->price;
                }

                // Check stock availability
                $product = $item->product;
                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception("Not enough stock available for {$product->name}");
                }

                // Deduct stock
                $product->stock_quantity -= $item->quantity;
                $product->save();

                // Calculate subtotal for this farmer's items
                $newSubtotal += $itemPrice * $item->quantity;
            }

            // Update order: remove negotiation flag and recalculate totals
            // For multi-vendor orders, we need to recalculate the entire order subtotal
            $allItemsSubtotal = 0;
            foreach ($order->items as $item) {
                $itemPrice = $item->negotiated_price ?? $item->price;
                $allItemsSubtotal += $itemPrice * $item->quantity;
            }

            $order->update([
                'is_negotiation' => false,
                'subtotal' => $allItemsSubtotal,
                'total' => $allItemsSubtotal + $order->shipping,
                'status' => 'pending' // Keep as pending, ready for processing
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Price negotiation accepted! Order is now confirmed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject price negotiation for an order
     */
    public function rejectNegotiation(Request $request, Order $order)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        // Check if this order contains products from the authenticated farmer
        $hasFarmerProducts = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();

        if (!$hasFarmerProducts) {
            abort(403, 'You can only reject negotiations for orders that contain your products.');
        }

        // Check if this is a negotiation order or has negotiated prices
        $hasNegotiatedPrices = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNotNull('negotiated_price')->exists();

        if (!$order->is_negotiation && !$hasNegotiatedPrices) {
            return redirect()->back()->with('error', 'This order is not a negotiation request.');
        }

        // Check if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'This negotiation has already been processed.');
        }

        try {
            // Update order status to cancelled
            $rejectionNote = $request->filled('rejection_reason')
                ? 'Rejected: ' . trim($request->rejection_reason)
                : 'Price negotiation rejected by farmer';

            $order->update([
                'status' => 'cancelled',
                'negotiation_notes' => ($order->negotiation_notes ? $order->negotiation_notes . "\n\n" : '') . $rejectionNote
            ]);

            return redirect()->back()->with('success', 'Price negotiation rejected. Order has been cancelled.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject negotiation: ' . $e->getMessage());
        }
    }
}
