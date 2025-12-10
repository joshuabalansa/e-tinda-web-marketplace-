<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
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
        $order = $user->orders()->with(['items.product', 'reviews'])->findOrFail($orderId);

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

    /**
     * Mark an order as received by the buyer.
     */
    public function markAsReceived(Request $request, $orderId)
    {
        $user = auth()->user();
        $order = $user->orders()->findOrFail($orderId);

        // Only allow marking as received if order is delivered
        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Only delivered orders can be marked as received.');
        }

        // Check if already received
        if ($order->isReceived()) {
            return redirect()->back()->with('info', 'This order has already been marked as received.');
        }

        try {
            $order->received_at = now();
            $order->save();

            return redirect()->route('buyer.orders.show', $order->id)
                           ->with('success', 'Order marked as received! You can now leave a review.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark order as received: ' . $e->getMessage());
        }
    }

    /**
     * Show the review form for an order.
     */
    public function showReviewForm($orderId)
    {
        $user = auth()->user();
        $order = $user->orders()->with(['items.product', 'reviews'])->findOrFail($orderId);

        // Check if order can be reviewed
        if (!$order->canBeReviewed()) {
            return redirect()->route('buyer.orders.show', $order->id)
                           ->with('error', 'This order cannot be reviewed yet. Please mark it as received first.');
        }

        // Get products that haven't been reviewed yet
        $reviewedProductIds = $order->reviews->pluck('product_id')->toArray();
        $productsToReview = $order->items->filter(function($item) use ($reviewedProductIds) {
            return $item->product && !in_array($item->product->id, $reviewedProductIds);
        });

        return view('buyer.orders.review', compact('order', 'productsToReview', 'reviewedProductIds'));
    }

    /**
     * Submit reviews for products in an order.
     */
    public function submitReview(Request $request, $orderId)
    {
        $user = auth()->user();
        $order = $user->orders()->with(['items.product'])->findOrFail($orderId);

        // Check if order can be reviewed
        if (!$order->canBeReviewed()) {
            return redirect()->route('buyer.orders.show', $order->id)
                           ->with('error', 'This order cannot be reviewed yet.');
        }

        $request->validate([
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $reviewedCount = 0;
            foreach ($request->reviews as $reviewData) {
                $productId = $reviewData['product_id'];

                // Verify product is in the order
                $orderItem = $order->items()->where('product_id', $productId)->first();
                if (!$orderItem) {
                    continue; // Skip if product not in order
                }

                // Check if already reviewed
                if (Review::hasReviewed($order->id, $productId, $user->id)) {
                    continue; // Skip if already reviewed
                }

                // Create review
                Review::create([
                    'order_id' => $order->id,
                    'buyer_id' => $user->id,
                    'product_id' => $productId,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'] ?? null,
                    'review_date' => now(),
                ]);

                $reviewedCount++;
            }

            DB::commit();

            if ($reviewedCount > 0) {
                return redirect()->route('buyer.orders.show', $order->id)
                               ->with('success', "Thank you! Your review(s) have been submitted successfully.");
            } else {
                return redirect()->route('buyer.orders.show', $order->id)
                               ->with('info', 'No new reviews were submitted. These products may have already been reviewed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit review: ' . $e->getMessage());
        }
    }
}
