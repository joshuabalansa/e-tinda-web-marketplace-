# Delivery Fee Calculation System

## Overview
This system automatically calculates delivery fees for the Philippines-based e-tinda marketplace based on customer address input. It uses a combination of Google Maps API (when available) and fallback city-based calculations.

## Features
- **Automatic Calculation**: Delivery fees are calculated automatically as users enter their address
- **Real-time Updates**: Fees update in real-time as users type their address
- **Multiple Calculation Methods**:
  - Google Maps Distance Matrix API (most accurate)
  - City-based fallback calculation (reliable backup)
- **Philippines-Specific**: Optimized for Philippine provinces, cities, and regions
- **Multi-vendor Support**: Handles orders from multiple farmers

## Setup Instructions

### 1. Google Maps API Setup (Optional but Recommended)
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable the following APIs:
   - Geocoding API
   - Distance Matrix API
   - Places API (optional)
4. Create an API key
5. Add the API key to your `.env` file:
   ```
   GOOGLE_MAPS_API_KEY=your_api_key_here
   ```

### 2. Configuration
The system works without Google Maps API, but with reduced accuracy. The fallback system uses predefined city coordinates and regional pricing.

## How It Works

### Calculation Methods

#### 1. Google Maps API Method (Primary)
- Uses Geocoding API to get precise coordinates
- Uses Distance Matrix API to calculate exact distance
- Calculates fee based on distance: `Base Fee + (Distance × Per KM Rate)`
- Provides accurate delivery time estimates

#### 2. City-Based Method (Fallback)
- Uses predefined coordinates for major Philippine cities
- Categorizes locations by region:
  - **Metro Manila**: ₱50 (Same day - Next day)
  - **Luzon**: ₱100 (2-3 days)
  - **Visayas**: ₱150 (3-5 days)
  - **Mindanao**: ₱200 (5-7 days)
  - **Remote Areas**: ₱250 (7-10 days)

### Fee Structure
- **Base Delivery Fee**: ₱50
- **Per Kilometer Rate**: ₱10
- **Maximum Delivery Fee**: ₱500
- **Free Delivery**: Farm pickup option

## Usage

### Frontend Integration
The checkout page automatically calculates delivery fees when:
1. User selects "Home Delivery" option
2. User enters address, city, and province
3. System waits 1 second after user stops typing
4. AJAX request is sent to calculate fee
5. UI updates with calculated fee and delivery time

### API Endpoint
```
POST /checkout/calculate-delivery-fee
```

**Request Body:**
```json
{
    "address": "123 Main Street",
    "city": "Quezon City",
    "province": "Metro Manila",
    "farmer_location": "Metro Manila, Philippines"
}
```

**Response:**
```json
{
    "success": true,
    "delivery_fee": 50,
    "distance": "5.2 km",
    "method": "google_maps",
    "estimated_delivery_time": "Same day - Next day"
}
```

## Customization

### Adjusting Fee Structure
Edit `app/Services/DeliveryFeeCalculator.php`:

```php
private $baseDeliveryFee = 50; // Base delivery fee in PHP
private $perKmRate = 10; // Additional fee per kilometer
private $maxDeliveryFee = 500; // Maximum delivery fee cap
```

### Adding New Cities
Add cities to the `$majorCities` array in the `DeliveryFeeCalculator` class:

```php
'Your City' => ['lat' => 14.5995, 'lng' => 120.9842],
```

### Regional Pricing
Modify the `calculateFeeFromCity()` method to adjust regional pricing:

```php
if ($isMetroManila) {
    return [
        'delivery_fee' => $this->baseDeliveryFee, // ₱50
        'distance' => 'Within Metro Manila',
        'method' => 'city_based',
        'estimated_delivery_time' => 'Same day - Next day'
    ];
}
```

## Error Handling
- If Google Maps API fails, system falls back to city-based calculation
- If city-based calculation fails, system uses default fee
- All errors are logged for debugging
- User-friendly error messages are displayed

## Testing
1. Test with Metro Manila addresses (should show ₱50)
2. Test with Luzon addresses (should show ₱100)
3. Test with Visayas addresses (should show ₱150)
4. Test with Mindanao addresses (should show ₱200)
5. Test with invalid addresses (should show default fee)

## Performance Considerations
- Google Maps API calls are cached (implement caching if needed)
- Fallback method is instant (no API calls)
- AJAX requests are debounced (1-second delay)
- Error handling prevents system failures

## Security
- CSRF protection on all requests
- Input validation and sanitization
- API key stored securely in environment variables
- Rate limiting recommended for production use

## Future Enhancements
- Implement caching for Google Maps API responses
- Add delivery time slots
- Support for different delivery speeds (express, standard, economy)
- Integration with local courier services
- Real-time tracking integration
- Delivery zone restrictions
