<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'subtotal',
        'shipping',
        'total',
        'status',
        'delivery_option',
        'payment_method',
        'special_instructions',
        'pickup_date',
        'delivery_date',
        'farmer_notes',
        'received_at'
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if the order has been received by the buyer
     */
    public function isReceived()
    {
        return $this->received_at !== null;
    }

    /**
     * Check if the order can be reviewed
     * Order must be delivered and received
     */
    public function canBeReviewed()
    {
        return $this->status === 'delivered' && $this->isReceived();
    }

    // Accessor for total_amount compatibility
    public function getTotalAmountAttribute()
    {
        return $this->total;
    }
}