<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BuyerDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Get the authenticated user's orders
        $orders = $user->orders()->with(['items.product'])->latest()->get();

        // Get wishlist items count
        $wishlistItems = $user->wishlist()->count();

        // Get cart items count from session
        $cartItems = count(Session::get('cart', []));

        // Get reviews count
        $reviews = $user->reviews()->count();

        return view('buyer.index', compact('orders', 'wishlistItems', 'cartItems', 'reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display buyer's orders.
     */
    public function orders()
    {
        $user = auth()->user();
        $orders = $user->orders()->with(['items.product'])->latest()->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Display buyer's order history.
     */
    public function history()
    {
        $user = auth()->user();
        $orders = $user->orders()->with(['items.product'])->latest()->get();

        return view('buyer.history.index', compact('orders'));
    }

    /**
     * Display a specific order.
     */
    public function showOrder($orderId)
    {
        $user = auth()->user();
        $order = $user->orders()->with(['items.product'])->findOrFail($orderId);

        return view('buyer.orders.show', compact('order'));
    }

    /**
     * Cancel a pending order.
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $user = auth()->user();
        $order = $user->orders()->with(['items.product'])->findOrFail($orderId);

        // Only allow cancellation of pending orders
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
        }

        try {
            DB::beginTransaction();

            // Restore stock for all items in the order
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->stock_quantity += $item->quantity;
                    $item->product->save();
                }
            }

            // Update order status to cancelled
            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return redirect()->route('buyer.orders.show', $order->id)
                           ->with('success', 'Order has been cancelled successfully. Stock has been restored.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}
