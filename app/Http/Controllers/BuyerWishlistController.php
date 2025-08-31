<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerWishlistController extends Controller
{
    /**
     * Display a listing of the buyer's wishlist items.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product.user')->latest()->paginate(12);

        return view('buyer.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the buyer's wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $userId)
                           ->where('product_id', $productId)
                           ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your wishlist'
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist successfully'
        ]);
    }

    /**
     * Remove a product from the buyer's wishlist.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist item not found'
            ]);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist successfully'
        ]);
    }

    /**
     * Remove a product from the buyer's wishlist by product ID.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeByProduct($productId)
    {
        $wishlistItem = Wishlist::where('product_id', $productId)
                                ->where('user_id', Auth::id())
                                ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in wishlist'
            ]);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist successfully'
        ]);
    }
}
