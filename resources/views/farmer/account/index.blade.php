@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-cog"></i> Account Settings</h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3>Account Settings</h3>
                            <p class="text-muted">Manage your account preferences and configurations.</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 20px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 20px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0" style="margin-top: 10px;">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Business Information Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-shop"></i> Business Information</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="business_name">Business Name</label>
                                                            <input type="text" class="form-control" id="business_name" name="business_name" value="{{ old('business_name', $user->business_name) }}" required>
                                                            @error('business_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="business_type">Business Type</label>
                                                            <select class="form-control" id="business_type" name="business_type" required>
                                                                <option value="">Select Business Type</option>
                                                                <option value="farm" {{ old('business_type', $user->business_type) == 'farm' ? 'selected' : '' }}>Farm</option>
                                                                <option value="greenhouse" {{ old('business_type', $user->business_type) == 'greenhouse' ? 'selected' : '' }}>Greenhouse</option>
                                                                <option value="orchard" {{ old('business_type', $user->business_type) == 'orchard' ? 'selected' : '' }}>Orchard</option>
                                                                <option value="dairy" {{ old('business_type', $user->business_type) == 'dairy' ? 'selected' : '' }}>Dairy Farm</option>
                                                                <option value="poultry" {{ old('business_type', $user->business_type) == 'poultry' ? 'selected' : '' }}>Poultry Farm</option>
                                                                <option value="other" {{ old('business_type', $user->business_type) == 'other' ? 'selected' : '' }}>Other</option>
                                                            </select>
                                                            @error('business_type') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="business_description">Business Description</label>
                                                            <textarea class="form-control" id="business_description" name="business_description" rows="4">{{ old('business_description', $user->business_description) }}</textarea>
                                                            @error('business_description') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="business_license">Business License Number</label>
                                                            <input type="text" class="form-control" id="business_license" name="business_license" value="{{ old('business_license', $user->business_license) }}">
                                                            @error('business_license') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tax_id">Tax ID</label>
                                                            <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id', $user->tax_id) }}">
                                                            @error('tax_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="entypo-pencil"></i> Update Business Information
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Settings Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-location"></i> Location Settings</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="farm_address">Farm Address</label>
                                                            <textarea class="form-control" id="farm_address" name="farm_address" rows="3" required>{{ old('farm_address', $user->farm_address) }}</textarea>
                                                            @error('farm_address') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="city">City</label>
                                                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}" required>
                                                            @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="state">State</label>
                                                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}" required>
                                                            @error('state') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="zip_code">ZIP Code</label>
                                                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}" required>
                                                            @error('zip_code') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="country">Country</label>
                                                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $user->country) }}" required>
                                                            @error('country') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="delivery_radius">Delivery Radius (km)</label>
                                                            <input type="number" class="form-control" id="delivery_radius" name="delivery_radius" value="{{ old('delivery_radius', $user->delivery_radius) }}" min="1" max="100" required>
                                                            @error('delivery_radius') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="coordinates">GPS Coordinates (Optional)</label>
                                                            <input type="hidden" id="coordinates" name="coordinates" value="{{ old('coordinates', $user->coordinates) }}">
                                                            <div id="coordinates-map" style="height: 300px; width: 100%; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;"></div>
                                                            <small class="text-muted" style="display: block; margin-top: 5px;">Click on the map to select your location. Selected coordinates: <span id="coordinates-display">{{ old('coordinates', $user->coordinates) ?: 'Not selected' }}</span></small>
                                                            @error('coordinates') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="entypo-map"></i> Update Location Settings
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Methods Form - HIDDEN
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-credit-card"></i> Payment Methods</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bank_name">Bank Name</label>
                                                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}">
                                                            @error('bank_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="account_number">Account Number</label>
                                                            <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number', $user->account_number) }}">
                                                            @error('account_number') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="routing_number">Routing Number</label>
                                                            <input type="text" class="form-control" id="routing_number" name="routing_number" value="{{ old('routing_number', $user->routing_number) }}">
                                                            @error('routing_number') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paypal_email">PayPal Email</label>
                                                            <input type="email" class="form-control" id="paypal_email" name="paypal_email" value="{{ old('paypal_email', $user->paypal_email) }}">
                                                            @error('paypal_email') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="preferred_payment_method">Preferred Payment Method</label>
                                                            <select class="form-control" id="preferred_payment_method" name="preferred_payment_method" required>
                                                                <option value="">Select Payment Method</option>
                                                                <option value="bank_transfer" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                                <option value="paypal" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                                                <option value="cash" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'cash' ? 'selected' : '' }}>Cash on Delivery</option>
                                                            </select>
                                                            @error('preferred_payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-info">
                                                    <i class="entypo-wallet"></i> Update Payment Methods
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            --}}

                            {{-- Privacy Settings Form - HIDDEN
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-shield"></i> Privacy Settings</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="profile_visibility">Profile Visibility</label>
                                                            <select class="form-control" id="profile_visibility" name="profile_visibility" required>
                                                                <option value="public" {{ old('profile_visibility', $user->profile_visibility) == 'public' ? 'selected' : '' }}>Public</option>
                                                                <option value="private" {{ old('profile_visibility', $user->profile_visibility) == 'private' ? 'selected' : '' }}>Private</option>
                                                                <option value="friends_only" {{ old('profile_visibility', $user->profile_visibility) == 'friends_only' ? 'selected' : '' }}>Friends Only</option>
                                                            </select>
                                                            @error('profile_visibility') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="show_contact_info" value="1" {{ $user->show_contact_info ? 'checked' : '' }}>
                                                                Show Contact Information
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="show_business_info" value="1" {{ $user->show_business_info ? 'checked' : '' }}>
                                                                Show Business Information
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="allow_messages" value="1" {{ $user->allow_messages ? 'checked' : '' }}>
                                                                Allow Messages from Buyers
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="data_sharing" value="1" {{ $user->data_sharing ? 'checked' : '' }}>
                                                                Allow Data Sharing for Analytics
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="entypo-eye"></i> Update Privacy Settings
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            --}}

                            {{-- Data Export - HIDDEN
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-download"></i> Data Export</h4>
                                        </div>
                                        <div class="panel-body">
                                            <p>Download a complete copy of your account data in JSON format. This includes all your personal information, business details, location settings, payment methods, privacy preferences, and notification settings.</p>
                                            <a href="{{ route('farmer.account.export-data') }}" class="btn btn-default">
                                                <i class="entypo-archive"></i> Export All Data
                                            </a>
                                            <small class="text-muted d-block mt-2">The exported file will be downloaded as a JSON file with timestamp.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Leaflet CSS for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<style>
    #coordinates-map {
        z-index: 1;
    }
    .leaflet-container {
        font-family: inherit;
    }
</style>
@endpush

@push('scripts')
<!-- Leaflet JS for map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
$(document).ready(function() {
    // Parse existing coordinates or use default (Philippines center)
    var defaultLat = 14.5995;
    var defaultLng = 120.9842;
    var initialZoom = 6;

    var coordinatesInput = $('#coordinates');
    var coordinatesDisplay = $('#coordinates-display');
    var existingCoords = coordinatesInput.val();

    // Parse existing coordinates if available
    if (existingCoords) {
        var coords = existingCoords.split(',').map(function(c) { return parseFloat(c.trim()); });
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            defaultLat = coords[0];
            defaultLng = coords[1];
            initialZoom = 15;
        }
    }

    // Initialize map
    var map = L.map('coordinates-map', {
        center: [defaultLat, defaultLng],
        zoom: initialZoom,
        zoomControl: true
    });

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Create marker (will be updated on click)
    var marker = null;

    // If we have existing coordinates, add marker
    if (existingCoords && defaultLat !== 14.5995 && defaultLng !== 120.9842) {
        marker = L.marker([defaultLat, defaultLng]).addTo(map);
        marker.bindPopup('Current location').openPopup();
    }

    // Handle map click to set coordinates
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        var coordsString = lat + ', ' + lng;

        // Update hidden input
        coordinatesInput.val(coordsString);

        // Update display
        coordinatesDisplay.text(coordsString);

        // Remove existing marker if any
        if (marker) {
            map.removeLayer(marker);
        }

        // Add new marker at clicked location
        marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup('Selected location: ' + coordsString).openPopup();
    });

    // Optional: Add geolocation button
    var geolocateControl = L.Control.extend({
        onAdd: function(map) {
            var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            container.innerHTML = '<a href="#" title="Use my current location" style="line-height: 30px; padding: 0 8px;"><i class="entypo-location" style="font-size: 18px;"></i></a>';
            container.style.backgroundColor = 'white';
            container.style.cursor = 'pointer';

            L.DomEvent.on(container, 'click', function(e) {
                L.DomEvent.stopPropagation(e);
                L.DomEvent.preventDefault(e);

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        var coordsString = lat + ', ' + lng;

                        // Update input
                        coordinatesInput.val(coordsString);
                        coordinatesDisplay.text(coordsString);

                        // Remove existing marker
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        // Add marker and center map
                        marker = L.marker([lat, lng]).addTo(map);
                        marker.bindPopup('Your current location: ' + coordsString).openPopup();
                        map.setView([lat, lng], 15);
                    }, function(error) {
                        alert('Unable to get your location. Please click on the map to select your location.');
                    });
                } else {
                    alert('Geolocation is not supported by your browser. Please click on the map to select your location.');
                }
            });

            return container;
        }
    });

    map.addControl(new geolocateControl({ position: 'topleft' }));
});
</script>
@endpush
@endsection
