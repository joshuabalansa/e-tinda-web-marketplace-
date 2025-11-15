<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Ensure the order contains products from this farmer
        $farmerOrderItems = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('product')->get();

        if ($farmerOrderItems->isEmpty()) {
            abort(403, 'Unauthorized access to this order.');
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
}
