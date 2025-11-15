<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryFeeCalculator
{
    private $googleMapsApiKey;
    private $baseDeliveryFee = 50; // Base delivery fee in PHP
    private $perKmRate = 10; // Additional fee per kilometer
    private $maxDeliveryFee = 500; // Maximum delivery fee cap

    // Major cities and their approximate coordinates for fallback calculation
    private $majorCities = [
        'Metro Manila' => ['lat' => 14.5995, 'lng' => 120.9842],
        'Quezon City' => ['lat' => 14.6760, 'lng' => 121.0437],
        'Manila' => ['lat' => 14.5995, 'lng' => 120.9842],
        'Makati' => ['lat' => 14.5547, 'lng' => 121.0244],
        'Taguig' => ['lat' => 14.5176, 'lng' => 121.0509],
        'Pasig' => ['lat' => 14.5764, 'lng' => 121.0851],
        'Mandaluyong' => ['lat' => 14.5794, 'lng' => 121.0359],
        'Marikina' => ['lat' => 14.6507, 'lng' => 121.1029],
        'Parañaque' => ['lat' => 14.4793, 'lng' => 121.0198],
        'Las Piñas' => ['lat' => 14.4506, 'lng' => 120.9828],
        'Muntinlupa' => ['lat' => 14.4081, 'lng' => 121.0365],
        'Caloocan' => ['lat' => 14.6548, 'lng' => 120.9823],
        'Malabon' => ['lat' => 14.6626, 'lng' => 120.9566],
        'Navotas' => ['lat' => 14.6783, 'lng' => 120.9419],
        'Valenzuela' => ['lat' => 14.6932, 'lng' => 120.9689],
        'San Juan' => ['lat' => 14.6019, 'lng' => 121.0355],
        'Pateros' => ['lat' => 14.5447, 'lng' => 121.0689],
        'Cebu City' => ['lat' => 10.3157, 'lng' => 123.8854],
        'Davao City' => ['lat' => 7.1907, 'lng' => 125.4553],
        'Iloilo City' => ['lat' => 10.7202, 'lng' => 122.5621],
        'Baguio' => ['lat' => 16.4023, 'lng' => 120.5960],
        'Cagayan de Oro' => ['lat' => 8.4542, 'lng' => 124.6319],
        'Zamboanga City' => ['lat' => 6.9214, 'lng' => 122.0790],
        'Antipolo' => ['lat' => 14.6255, 'lng' => 121.1245],
        'Cainta' => ['lat' => 14.5968, 'lng' => 121.1222],
        'Taytay' => ['lat' => 14.5692, 'lng' => 121.1325],
        'Angeles' => ['lat' => 15.1449, 'lng' => 120.5906],
        'Olongapo' => ['lat' => 14.8292, 'lng' => 120.2828],
        'Bacolod' => ['lat' => 10.6407, 'lng' => 122.9687],
        'Iligan' => ['lat' => 8.2280, 'lng' => 124.2452],
        'General Santos' => ['lat' => 6.1168, 'lng' => 125.1716],
        'Dumaguete' => ['lat' => 9.3068, 'lng' => 123.3045],
        'Tagbilaran' => ['lat' => 9.6641, 'lng' => 123.8523],
        'Puerto Princesa' => ['lat' => 9.8349, 'lng' => 118.7384],
        'Legazpi' => ['lat' => 13.1390, 'lng' => 123.7440],
        'Naga' => ['lat' => 13.6192, 'lng' => 123.1814],
        'Tuguegarao' => ['lat' => 17.6138, 'lng' => 121.7269],
        'Vigan' => ['lat' => 17.5748, 'lng' => 120.3869],
        'Laoag' => ['lat' => 18.1978, 'lng' => 120.5927],
        'San Fernando' => ['lat' => 15.0316, 'lng' => 120.6858],
        'Malolos' => ['lat' => 14.8448, 'lng' => 120.8106],
        'Balanga' => ['lat' => 14.6760, 'lng' => 120.5361],
        'Calapan' => ['lat' => 13.4125, 'lng' => 121.1800],
        'Lucena' => ['lat' => 13.9314, 'lng' => 121.6172],
        'Batangas City' => ['lat' => 13.7563, 'lng' => 121.0583],
        'Lipa' => ['lat' => 13.9408, 'lng' => 121.1631],
        'San Pablo' => ['lat' => 14.0689, 'lng' => 121.3256],
        'Biñan' => ['lat' => 14.3433, 'lng' => 121.0806],
        'Santa Rosa' => ['lat' => 14.3122, 'lng' => 121.1114],
        'Calamba' => ['lat' => 14.2116, 'lng' => 121.1652],
        'Los Baños' => ['lat' => 14.1667, 'lng' => 121.2167],
        'San Pedro' => ['lat' => 14.3583, 'lng' => 121.0167],
        'Cabuyao' => ['lat' => 14.2725, 'lng' => 121.1261],
        'Sta. Cruz' => ['lat' => 14.2814, 'lng' => 121.4156],
        'Alaminos' => ['lat' => 14.0639, 'lng' => 121.2461],
        'Tanauan' => ['lat' => 14.0833, 'lng' => 121.1500],
        'Talisay' => ['lat' => 14.1333, 'lng' => 121.0167],
        'Santo Tomas' => ['lat' => 14.1167, 'lng' => 121.1333],
        'Malvar' => ['lat' => 14.0500, 'lng' => 121.1500],
        'Taysan' => ['lat' => 13.8667, 'lng' => 121.2000],
        'Rosario' => ['lat' => 13.8500, 'lng' => 121.2000],
        'Padre Garcia' => ['lat' => 13.8667, 'lng' => 121.2167],
        'San Jose' => ['lat' => 13.8833, 'lng' => 121.1000],
        'Ibaan' => ['lat' => 13.8167, 'lng' => 121.1333],
        'Tuy' => ['lat' => 13.8000, 'lng' => 120.9833],
        'Balayan' => ['lat' => 13.9333, 'lng' => 120.7333],
        'Calatagan' => ['lat' => 13.8333, 'lng' => 120.6333],
        'Lian' => ['lat' => 14.0333, 'lng' => 120.6500],
        'Nasugbu' => ['lat' => 14.0667, 'lng' => 120.6333],
        'Ternate' => ['lat' => 14.2833, 'lng' => 120.7167],
        'Maragondon' => ['lat' => 14.2667, 'lng' => 120.7333],
        'Magallanes' => ['lat' => 14.2000, 'lng' => 120.7500],
        'Gen. Emilio Aguinaldo' => ['lat' => 14.1833, 'lng' => 120.8000],
        'Indang' => ['lat' => 14.2000, 'lng' => 120.8833],
        'Alfonso' => ['lat' => 14.1333, 'lng' => 120.8500],
        'General Trias' => ['lat' => 14.3833, 'lng' => 120.8833],
        'Silang' => ['lat' => 14.2167, 'lng' => 120.9667],
        'Amadeo' => ['lat' => 14.1667, 'lng' => 120.9167],
        'Trece Martires' => ['lat' => 14.2833, 'lng' => 120.8667],
        'Dasmariñas' => ['lat' => 14.3294, 'lng' => 120.9367],
        'Imus' => ['lat' => 14.4297, 'lng' => 120.9367],
        'Kawit' => ['lat' => 14.4333, 'lng' => 120.9000],
        'Noveleta' => ['lat' => 14.4333, 'lng' => 120.8833],
        'Rosario' => ['lat' => 14.4167, 'lng' => 120.8500],
        'Tanza' => ['lat' => 14.4000, 'lng' => 120.8500],
        'Naic' => ['lat' => 14.3167, 'lng' => 120.7667],
        'Carmona' => ['lat' => 14.3167, 'lng' => 121.0500],
        'GMA' => ['lat' => 14.2833, 'lng' => 121.0167],
        'Bacoor' => ['lat' => 14.4583, 'lng' => 120.9583],
    ];

    public function __construct()
    {
        $this->googleMapsApiKey = config('services.google_maps.api_key');
    }

    /**
     * Calculate delivery fee based on customer address
     *
     * @param string $customerAddress
     * @param string $customerCity
     * @param string $customerProvince
     * @param string $farmerLocation (optional - farmer's city/province)
     * @return array
     */
    public function calculateDeliveryFee($customerAddress, $customerCity, $customerProvince, $farmerLocation = null)
    {
        try {
            // First try to get precise coordinates using Google Maps API
            $customerCoords = $this->geocodeAddress($customerAddress, $customerCity, $customerProvince);

            if ($customerCoords) {
                // Use Google Maps Distance Matrix API for accurate calculation
                $distance = $this->getDistanceFromGoogleMaps($customerCoords, $farmerLocation);

                if ($distance) {
                    return $this->calculateFeeFromDistance($distance);
                }
            }

            // Fallback to city-based calculation
            return $this->calculateFeeFromCity($customerCity, $customerProvince, $farmerLocation);

        } catch (\Exception $e) {
            Log::error('Delivery fee calculation error: ' . $e->getMessage());

            // Return default fee structure
            return [
                'delivery_fee' => $this->baseDeliveryFee,
                'distance' => 'Unknown',
                'method' => 'default',
                'estimated_delivery_time' => '2-3 days'
            ];
        }
    }

    /**
     * Geocode address using Google Maps Geocoding API
     */
    private function geocodeAddress($address, $city, $province)
    {
        if (!$this->googleMapsApiKey) {
            return null;
        }

        $fullAddress = "{$address}, {$city}, {$province}, Philippines";

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $fullAddress,
            'key' => $this->googleMapsApiKey
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if ($data['status'] === 'OK' && !empty($data['results'])) {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng']
                ];
            }
        }

        return null;
    }

    /**
     * Get distance using Google Maps Distance Matrix API
     */
    private function getDistanceFromGoogleMaps($customerCoords, $farmerLocation = null)
    {
        if (!$this->googleMapsApiKey) {
            return null;
        }

        // Default origin (you can set this to your main warehouse or most common farmer location)
        $origin = $farmerLocation ? $farmerLocation : 'Metro Manila, Philippines';
        $destination = "{$customerCoords['lat']},{$customerCoords['lng']}";

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => $origin,
            'destinations' => $destination,
            'units' => 'metric',
            'key' => $this->googleMapsApiKey
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if ($data['status'] === 'OK' &&
                !empty($data['rows'][0]['elements'][0]) &&
                $data['rows'][0]['elements'][0]['status'] === 'OK') {

                $element = $data['rows'][0]['elements'][0];
                return $element['distance']['value'] / 1000; // Convert meters to kilometers
            }
        }

        return null;
    }

    /**
     * Calculate fee based on distance
     */
    private function calculateFeeFromDistance($distanceKm)
    {
        $deliveryFee = $this->baseDeliveryFee + ($distanceKm * $this->perKmRate);
        $deliveryFee = min($deliveryFee, $this->maxDeliveryFee);

        // Determine delivery time based on distance
        $deliveryTime = $this->getDeliveryTime($distanceKm);

        return [
            'delivery_fee' => round($deliveryFee),
            'distance' => round($distanceKm, 1) . ' km',
            'method' => 'google_maps',
            'estimated_delivery_time' => $deliveryTime
        ];
    }

    /**
     * Calculate fee based on city (fallback method)
     */
    private function calculateFeeFromCity($city, $province, $farmerLocation = null)
    {
        $customerLocation = $city . ', ' . $province;
        $origin = $farmerLocation ?: 'Metro Manila';

        // Check if customer is in Metro Manila
        $metroManilaCities = [
            'Quezon City', 'Manila', 'Makati', 'Taguig', 'Pasig', 'Mandaluyong',
            'Marikina', 'Parañaque', 'Las Piñas', 'Muntinlupa', 'Caloocan',
            'Malabon', 'Navotas', 'Valenzuela', 'San Juan', 'Pateros'
        ];

        $isMetroManila = in_array($city, $metroManilaCities) ||
                        stripos($province, 'Metro Manila') !== false ||
                        stripos($province, 'NCR') !== false;

        if ($isMetroManila) {
            return [
                'delivery_fee' => $this->baseDeliveryFee,
                'distance' => 'Within Metro Manila',
                'method' => 'city_based',
                'estimated_delivery_time' => 'Same day - Next day'
            ];
        }

        // Check if customer is in Luzon (outside Metro Manila)
        $luzonProvinces = [
            'Bulacan', 'Cavite', 'Laguna', 'Rizal', 'Pampanga', 'Bataan',
            'Zambales', 'Tarlac', 'Nueva Ecija', 'Aurora', 'Quezon',
            'Batangas', 'Camarines Norte', 'Camarines Sur', 'Catanduanes',
            'Albay', 'Sorsogon', 'Masbate', 'Romblon', 'Marinduque',
            'Occidental Mindoro', 'Oriental Mindoro', 'Palawan'
        ];

        $isLuzon = in_array($province, $luzonProvinces);

        if ($isLuzon) {
            return [
                'delivery_fee' => $this->baseDeliveryFee + 50,
                'distance' => 'Luzon Region',
                'method' => 'city_based',
                'estimated_delivery_time' => '2-3 days'
            ];
        }

        // Check if customer is in Visayas
        $visayasProvinces = [
            'Cebu', 'Bohol', 'Negros Oriental', 'Negros Occidental',
            'Iloilo', 'Capiz', 'Aklan', 'Antique', 'Guimaras',
            'Leyte', 'Southern Leyte', 'Eastern Samar', 'Western Samar',
            'Northern Samar', 'Biliran', 'Siquijor'
        ];

        $isVisayas = in_array($province, $visayasProvinces);

        if ($isVisayas) {
            return [
                'delivery_fee' => $this->baseDeliveryFee + 100,
                'distance' => 'Visayas Region',
                'method' => 'city_based',
                'estimated_delivery_time' => '3-5 days'
            ];
        }

        // Check if customer is in Mindanao
        $mindanaoProvinces = [
            'Davao del Sur', 'Davao del Norte', 'Davao Oriental', 'Davao de Oro',
            'Davao Occidental', 'Cotabato', 'South Cotabato', 'Sultan Kudarat',
            'Sarangani', 'General Santos', 'Zamboanga del Sur', 'Zamboanga del Norte',
            'Zamboanga Sibugay', 'Zamboanga City', 'Misamis Oriental', 'Misamis Occidental',
            'Bukidnon', 'Camiguin', 'Lanao del Norte', 'Lanao del Sur',
            'Maguindanao', 'Sulu', 'Tawi-Tawi', 'Basilan', 'Agusan del Norte',
            'Agusan del Sur', 'Surigao del Norte', 'Surigao del Sur', 'Dinagat Islands'
        ];

        $isMindanao = in_array($province, $mindanaoProvinces);

        if ($isMindanao) {
            return [
                'delivery_fee' => $this->baseDeliveryFee + 150,
                'distance' => 'Mindanao Region',
                'method' => 'city_based',
                'estimated_delivery_time' => '5-7 days'
            ];
        }

        // Default for unknown locations
        return [
            'delivery_fee' => $this->baseDeliveryFee + 200,
            'distance' => 'Remote Area',
            'method' => 'city_based',
            'estimated_delivery_time' => '7-10 days'
        ];
    }

    /**
     * Get estimated delivery time based on distance
     */
    private function getDeliveryTime($distanceKm)
    {
        if ($distanceKm <= 10) {
            return 'Same day - Next day';
        } elseif ($distanceKm <= 50) {
            return '1-2 days';
        } elseif ($distanceKm <= 100) {
            return '2-3 days';
        } elseif ($distanceKm <= 200) {
            return '3-4 days';
        } elseif ($distanceKm <= 500) {
            return '4-5 days';
        } else {
            return '5-7 days';
        }
    }

    /**
     * Get delivery zones and their fees
     */
    public function getDeliveryZones()
    {
        return [
            'metro_manila' => [
                'name' => 'Metro Manila',
                'fee' => $this->baseDeliveryFee,
                'delivery_time' => 'Same day - Next day',
                'cities' => [
                    'Quezon City', 'Manila', 'Makati', 'Taguig', 'Pasig', 'Mandaluyong',
                    'Marikina', 'Parañaque', 'Las Piñas', 'Muntinlupa', 'Caloocan',
                    'Malabon', 'Navotas', 'Valenzuela', 'San Juan', 'Pateros'
                ]
            ],
            'luzon' => [
                'name' => 'Luzon Region',
                'fee' => $this->baseDeliveryFee + 50,
                'delivery_time' => '2-3 days',
                'provinces' => [
                    'Bulacan', 'Cavite', 'Laguna', 'Rizal', 'Pampanga', 'Bataan',
                    'Zambales', 'Tarlac', 'Nueva Ecija', 'Aurora', 'Quezon',
                    'Batangas', 'Camarines Norte', 'Camarines Sur', 'Catanduanes',
                    'Albay', 'Sorsogon', 'Masbate', 'Romblon', 'Marinduque',
                    'Occidental Mindoro', 'Oriental Mindoro', 'Palawan'
                ]
            ],
            'visayas' => [
                'name' => 'Visayas Region',
                'fee' => $this->baseDeliveryFee + 100,
                'delivery_time' => '3-5 days',
                'provinces' => [
                    'Cebu', 'Bohol', 'Negros Oriental', 'Negros Occidental',
                    'Iloilo', 'Capiz', 'Aklan', 'Antique', 'Guimaras',
                    'Leyte', 'Southern Leyte', 'Eastern Samar', 'Western Samar',
                    'Northern Samar', 'Biliran', 'Siquijor'
                ]
            ],
            'mindanao' => [
                'name' => 'Mindanao Region',
                'fee' => $this->baseDeliveryFee + 150,
                'delivery_time' => '5-7 days',
                'provinces' => [
                    'Davao del Sur', 'Davao del Norte', 'Davao Oriental', 'Davao de Oro',
                    'Davao Occidental', 'Cotabato', 'South Cotabato', 'Sultan Kudarat',
                    'Sarangani', 'General Santos', 'Zamboanga del Sur', 'Zamboanga del Norte',
                    'Zamboanga Sibugay', 'Zamboanga City', 'Misamis Oriental', 'Misamis Occidental',
                    'Bukidnon', 'Camiguin', 'Lanao del Norte', 'Lanao del Sur',
                    'Maguindanao', 'Sulu', 'Tawi-Tawi', 'Basilan', 'Agusan del Norte',
                    'Agusan del Sur', 'Surigao del Norte', 'Surigao del Sur', 'Dinagat Islands'
                ]
            ]
        ];
    }
}
