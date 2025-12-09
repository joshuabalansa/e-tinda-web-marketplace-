<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_picture',
        'business_name',
        'business_type',
        'business_description',
        'business_license',
        'tax_id',
        'farm_address',
        'city',
        'state',
        'zip_code',
        'country',
        'delivery_radius',
        'coordinates',
        'bank_name',
        'account_number',
        'routing_number',
        'paypal_email',
        'preferred_payment_method',
        'profile_visibility',
        'show_contact_info',
        'show_business_info',
        'allow_messages',
        'data_sharing',
        'email_notifications',
        'sms_notifications',
        'order_notifications',
        'marketing_notifications',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isFarmer(): bool
    {
        return $this->role === UserRole::Farmer;
    }

    public function isBuyer(): bool
    {
        return $this->role === UserRole::Buyer;
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the wishlist items for the user.
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the reviews for the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }

    /**
     * Get the products for the user (farmer).
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get formatted location string for display.
     * Uses farm_address, city, state, country fields.
     */
    public function getFormattedLocation()
    {
        $parts = [];

        if ($this->farm_address) {
            $parts[] = $this->farm_address;
        }

        if ($this->city) {
            $parts[] = $this->city;
        }

        if ($this->state) {
            $parts[] = $this->state;
        }

        if ($this->country) {
            $parts[] = $this->country;
        }

        // If we have any location data, return it
        if (!empty($parts)) {
            return implode(', ', $parts);
        }

        // Fallback to address field if available
        if ($this->address) {
            return $this->address;
        }

        return 'Location not specified';
    }
}
