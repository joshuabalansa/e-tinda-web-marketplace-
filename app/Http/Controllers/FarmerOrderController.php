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

        return view('farmer.orders.show', compact('order', 'farmerOrderItems'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
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
            'cancelled_orders' => 0,
            'total_revenue' => 0
        ];

        // Get orders with this farmer's products
        $orders = Order::whereHas('items.product', function($query) use ($farmerId) {
            $query->where('user_id', $farmerId);
        })->with('items.product')->get();

        foreach ($orders as $order) {
            $stats['total_orders']++;
            $stats[$order->status . '_orders']++;

            // Calculate revenue from this farmer's products in the order
            foreach ($order->items as $item) {
                if ($item->product->user_id === $farmerId) {
                    $stats['total_revenue'] += $item->price * $item->quantity;
                }
            }
        }

        return $stats;
    }
}