<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'farmer_id',
        'product_id',
        'quantity_in',
        'quantity_out',
        'current_stock',
        'unit_cost',
        'total_value',
        'transaction_type',
        'notes',
        'transaction_date',
        'reference_number',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    /**
     * Get the farmer that owns the inventory record.
     */
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Get the product associated with the inventory record.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to filter by farmer.
     */
    public function scopeForFarmer($query, $farmerId)
    {
        return $query->where('farmer_id', $farmerId);
    }

    /**
     * Scope to filter by product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to filter by transaction type.
     */
    public function scopeByTransactionType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
