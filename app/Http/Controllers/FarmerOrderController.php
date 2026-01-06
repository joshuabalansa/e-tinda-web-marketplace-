<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FarmerOrderController extends Controller
{
    /**
     * Display a listing of orders that contain products from the authenticated farmer.
     */
    public function index()
    {
        $farmerId = Auth::id();

        // Get orders that contain products from this farmer
        $orders = Order::whereHas('items.product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })
        ->with(['items.product', 'user'])
        ->latest()
        ->paginate(15);

        // Get order statistics
        $stats = $this->getOrderStats($farmerId);

        return view('farmer.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $farmerId = Auth::id();

        // Check if this order contains products from the authenticated farmer
        $farmerOrderItems = $order->items()->whereHas('product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })->with('product')->get();

        if ($farmerOrderItems->isEmpty()) {
            abort(403, 'You can only view orders that contain your products.');
        }

        $order->load(['user', 'items.product.user']);

        // Debug: Verify order calculations
        if (config('app.debug')) {
            $calculatedSubtotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            \Log::info('Order Calculation Debug', [
                'order_id' => $order->id,
                'stored_subtotal' => $order->subtotal,
                'calculated_subtotal' => $calculatedSubtotal,
                'stored_shipping' => $order->shipping,
                'stored_total' => $order->total,
                'calculated_total' => $calculatedSubtotal + $order->shipping,
                'farmer_items_count' => $farmerOrderItems->count(),
                'total_items_count' => $order->items->count()
            ]);
        }

        return view('farmer.orders.show', compact('order', 'farmerOrderItems'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $farmerId = Auth::id();

        // Check if this order contains products from the authenticated farmer
        $hasFarmerProducts = $order->items()->whereHas('product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })->exists();

        if (!$hasFarmerProducts) {
            abort(403, 'You can only update orders that contain your products.');
        }

        $order->update(['status' => $request->status]);

        return redirect()->back();
    }

    /**
     * Get order statistics for the farmer.
     */
    private function getOrderStats($farmerId)
    {
        $stats = [
            'total_orders' => 0,
            'pending_orders' => 0,
            'processing_orders' => 0,
            'shipped_orders' => 0,
            'delivered_orders' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0,
            'total_revenue' => 0
        ];

        // Get orders with this farmer's products
        $orders = Order::whereHas('items.product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })->with('items.product')->get();

        foreach ($orders as $order) {
            $stats['total_orders']++;

            // Safely increment the status-specific counter
            $statusKey = $order->status . '_orders';
            if (isset($stats[$statusKey])) {
                $stats[$statusKey]++;
            }

            // Calculate revenue from this farmer's products in the order
            foreach ($order->items as $item) {
                if ($item->product->user_id === $farmerId) {
                    $stats['total_revenue'] += $item->price * $item->quantity;
                }
            }
        }

        return $stats;
    }

    /**
     * Accept price negotiation for an order
     */
    public function acceptNegotiation(Request $request, Order $order)
    {
        $farmerId = Auth::id();

        // Check if this order contains products from the authenticated farmer
        $farmerOrderItems = $order->items()->whereHas('product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })->with('product')->get();

        if ($farmerOrderItems->isEmpty()) {
            abort(403, 'You can only accept negotiations for orders that contain your products.');
        }

        // Check if this is a negotiation order
        if (!$order->is_negotiation) {
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

        $farmerId = Auth::id();

        // Check if this order contains products from the authenticated farmer
        $hasFarmerProducts = $order->items()->whereHas('product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })->exists();

        if (!$hasFarmerProducts) {
            abort(403, 'You can only reject negotiations for orders that contain your products.');
        }

        // Check if this is a negotiation order
        if (!$order->is_negotiation) {
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