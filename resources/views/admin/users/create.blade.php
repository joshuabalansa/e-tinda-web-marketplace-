@extends('layouts.admin')
@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Register New User</h4>
                </div>
                <div class="panel-options">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                        <i class="entypo-left"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">User Information</div>
            </div>
            <div class="panel-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Account Type *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select account type</option>
                                    <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="business_name">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" value="{{ old('business_name') }}">
                                @error('business_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Farmer Location Information Section -->
                    <div id="farmer-location-fields" style="display: none;">
                        <hr>
                        <h5 class="mb-3"><i class="entypo-location"></i> Farm Location Information</h5>

                        <div class="form-group">
                            <label for="farm_address">Farm Address *</label>
                            <textarea class="form-control" id="farm_address" name="farm_address" rows="3">{{ old('farm_address') }}</textarea>
                            @error('farm_address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City/Municipality *</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">Province *</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="zip_code">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                                    @error('zip_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Philippines') }}">
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                Active User (Account will be active immediately)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="entypo-user-add"></i> Register User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                            <i class="entypo-cancel"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .panel {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-control {
        border-radius: 4px;
    }
    .btn {
        margin-right: 10px;
    }
    .alert {
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide farmer location fields based on role selection
        const roleSelect = document.getElementById('role');
        const farmerLocationFields = document.getElementById('farmer-location-fields');
        const farmAddressField = document.getElementById('farm_address');
        const cityField = document.getElementById('city');
        const stateField = document.getElementById('state');

        function toggleFarmerFields() {
            if (roleSelect.value === 'farmer') {
                farmerLocationFields.style.display = 'block';
                // Make location fields required when farmer is selected
                if (farmAddressField) farmAddressField.setAttribute('required', 'required');
                if (cityField) cityField.setAttribute('required', 'required');
                if (stateField) stateField.setAttribute('required', 'required');
            } else {
                farmerLocationFields.style.display = 'none';
                // Remove required attribute when not farmer
                if (farmAddressField) farmAddressField.removeAttribute('required');
                if (cityField) cityField.removeAttribute('required');
                if (stateField) stateField.removeAttribute('required');
            }
        }

        // Initial check
        toggleFarmerFields();

        // Listen for changes
        if (roleSelect) {
            roleSelect.addEventListener('change', toggleFarmerFields);
        }

        // Password match validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const form = document.querySelector('form');

        if (form && password && confirmPassword) {
            form.addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }
            });

            confirmPassword.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                } else {
                    confirmPassword.classList.remove('is-invalid');
                }
            });
        }
    });
</script>
@endpush

