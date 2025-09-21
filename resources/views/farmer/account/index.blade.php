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

                            <!-- Business Information Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-shop"></i> Business Information</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update-business') }}" method="POST">
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
                                            <form action="{{ route('farmer.account.update-location') }}" method="POST">
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
                                                            <input type="text" class="form-control" id="coordinates" name="coordinates" value="{{ old('coordinates', $user->coordinates) }}" placeholder="e.g., 14.5995, 120.9842">
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

                            <!-- Payment Methods Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-credit-card"></i> Payment Methods</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update-payment') }}" method="POST">
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

                            <!-- Privacy Settings Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-shield"></i> Privacy Settings</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.account.update-privacy') }}" method="POST">
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

                            <!-- Data Export -->
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
