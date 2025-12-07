<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessor for price compatibility
    public function getPriceAttribute()
    {
        return $this->price_per_unit;
    }

    /**
     * Get the full URL for the product image
     * Works in both local and production environments
     */
    public function getImageUrl()
    {
        $imagePath = $this->getAttribute('image_url');
        $productName = $this->name ?? 'Product';
        // Format product name for placeholder: replace spaces with +
        $placeholderText = str_replace(' ', '+', $productName);

        // If no image path, return placeholder
        if (!$imagePath || trim($imagePath) === '') {
            return 'https://placehold.co/600x400?text=' . $placeholderText;
        }

        // If it's already a full URL, return it as is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // Use Storage::url() for proper URL generation
        // This respects the filesystem configuration and works in both local and production
        try {
            return Storage::disk('public')->url($imagePath);
        } catch (\Exception $e) {
            // Fallback to asset() if Storage fails
            return asset('storage/' . ltrim($imagePath, '/'));
        }
    }
}
