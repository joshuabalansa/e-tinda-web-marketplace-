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
        'association_id',
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
        if (!$this->role) {
            return false;
        }
        return $this->role === UserRole::Admin;
    }

    public function isFarmer(): bool
    {
        if (!$this->role) {
            return false;
        }
        return $this->role === UserRole::Farmer;
    }

    public function isBuyer(): bool
    {
        if (!$this->role) {
            return false;
        }
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
     * Get the association (cooperative) this farmer belongs to.
     */
    public function association()
    {
        return $this->belongsTo(Association::class, 'association_id', 'association_id');
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

    /**
     * Parse coordinates string into latitude and longitude array.
     * Expected format: "lat, lng" or "lat,lng"
     *
     * @return array|null Returns ['lat' => float, 'lng' => float] or null if invalid
     */
    public function getCoordinatesArray()
    {
        if (!$this->coordinates) {
            return null;
        }

        // Remove whitespace and split by comma
        $coords = array_map('trim', explode(',', $this->coordinates));

        if (count($coords) !== 2) {
            return null;
        }

        $lat = filter_var($coords[0], FILTER_VALIDATE_FLOAT);
        $lng = filter_var($coords[1], FILTER_VALIDATE_FLOAT);

        // Validate latitude (-90 to 90) and longitude (-180 to 180)
        if ($lat === false || $lng === false) {
            return null;
        }

        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return null;
        }

        return [
            'lat' => (float) $lat,
            'lng' => (float) $lng
        ];
    }

    /**
     * Check if user has valid GPS coordinates.
     *
     * @return bool
     */
    public function hasValidCoordinates()
    {
        return $this->getCoordinatesArray() !== null;
    }
}
