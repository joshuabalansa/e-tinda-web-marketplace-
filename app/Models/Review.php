<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'order_id',
        'buyer_id',
        'product_id',
        'rating',
        'comment',
        'review_date'
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'rating' => 'integer'
    ];

    /**
     * Validation rules for reviews
     */
    public static function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'product_id' => 'required|exists:products,id',
        ];
    }

    /**
     * Check if a product has already been reviewed by a buyer in an order
     */
    public static function hasReviewed($orderId, $productId, $buyerId)
    {
        return self::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->where('buyer_id', $buyerId)
            ->exists();
    }

    /**
     * Get the order that the review belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the buyer that wrote the review.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the product that was reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}




































