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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate the average rating for this product
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get the total number of reviews for this product
     */
    public function totalReviews()
    {
        return $this->reviews()->count();
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
        // This works with both local storage and Laravel Cloud buckets
        try {
            // Check if the disk driver is available before using it
            $disk = Storage::disk('public');
            $driver = config('filesystems.disks.public.driver', 'local');

            // If S3 driver is configured but not available, use fallback
            if ($driver === 's3') {
                try {
                    return $disk->url($imagePath);
                } catch (\Exception $e) {
                    // S3 driver not available, fallback to local URL
                    return url('storage/' . ltrim($imagePath, '/'));
                }
            }

            return $disk->url($imagePath);
        } catch (\Exception $e) {
            // Fallback to storage route if direct URL fails
            return url('storage/' . ltrim($imagePath, '/'));
        }
    }
}
