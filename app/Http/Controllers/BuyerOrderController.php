<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerOrderController extends Controller
{
    /**
     * Display a listing of the buyer's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with(['items.product.user'])->latest()->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated buyer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.product.user']);

        return view('buyer.orders.show', compact('order'));
    }
}
