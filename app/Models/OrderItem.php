<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'negotiated_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor for subtotal calculation
    public function getSubtotalAttribute()
    {
        // Use negotiated price if available, otherwise use regular price
        $price = $this->negotiated_price ?? $this->price;
        return $this->quantity * $price;
    }

    /**
     * Get the effective price (negotiated or regular)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->negotiated_price ?? $this->price;
    }

    /**
     * Check if this item has a negotiated price
     */
    public function hasNegotiatedPrice()
    {
        return $this->negotiated_price !== null;
    }
}