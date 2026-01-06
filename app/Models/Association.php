<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    use HasFactory;

    protected $primaryKey = 'association_id';

    protected $fillable = [
        'name',
        'location',
        'total_members',
        'date_established',
        'contact_person',
        'slug',
        'description',
        'logo_url',
        'banner_url',
        'is_active',
        'featured',
    ];

    protected $casts = [
        'date_established' => 'date',
        'total_members' => 'integer',
        'is_active' => 'boolean',
        'featured' => 'boolean',
    ];

    /**
     * Get the farmers (users) that belong to this association
     */
    public function farmers()
    {
        return $this->hasMany(User::class, 'association_id')->where('role', 'farmer');
    }

    /**
     * Get all products from farmers in this association
     * This is a helper method that returns a query builder
     */
    public function getProductsQuery()
    {
        return Product::whereHas('user', function($query) {
            $query->where('association_id', $this->association_id)
                  ->where('role', 'farmer');
        });
    }

    /**
     * Get available products from farmers in this association
     */
    public function getAvailableProductsQuery()
    {
        return $this->getProductsQuery()->where('status', 'available');
    }

    /**
     * Get the URL for the association logo
     */
    public function getLogoUrl()
    {
        if (!$this->logo_url || trim($this->logo_url) === '') {
            return asset('assets/images/placeholder.jpg');
        }

        if (filter_var($this->logo_url, FILTER_VALIDATE_URL)) {
            return $this->logo_url;
        }

        $cleanPath = ltrim($this->logo_url, '/');
        $cleanPath = preg_replace('/^storage\//', '', $cleanPath);

        if (app()->environment('production')) {
            $baseUrl = rtrim(config('app.url'), '/');
            return $baseUrl . '/storage/' . $cleanPath;
        }

        return url('storage/' . $cleanPath);
    }

    /**
     * Get the URL for the association banner
     */
    public function getBannerUrl()
    {
        if (!$this->banner_url || trim($this->banner_url) === '') {
            return null;
        }

        if (filter_var($this->banner_url, FILTER_VALIDATE_URL)) {
            return $this->banner_url;
        }

        $cleanPath = ltrim($this->banner_url, '/');
        $cleanPath = preg_replace('/^storage\//', '', $cleanPath);

        if (app()->environment('production')) {
            $baseUrl = rtrim(config('app.url'), '/');
            return $baseUrl . '/storage/' . $cleanPath;
        }

        return url('storage/' . $cleanPath);
    }

    /**
     * Update total_members count based on actual farmers
     */
    public function updateMemberCount()
    {
        $this->total_members = $this->farmers()->count();
        $this->save();
    }
}

