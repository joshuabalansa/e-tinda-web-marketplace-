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
        'farmer_notes'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for total_amount compatibility
    public function getTotalAmountAttribute()
    {
        return $this->total;
    }
}