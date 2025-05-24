<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price_per_unit',
        'unit_type',
        'stock_quantity',
        'harvest_date',
        'image_url',
        'category',
        'status'
    ];

    protected $casts = [
        'harvest_date' => 'date',
        'price_per_unit' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
