<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
}
