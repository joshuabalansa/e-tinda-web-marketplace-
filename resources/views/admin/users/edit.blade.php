@extends('layouts.admin')
@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Edit User: {{ $user->name }}</h4>
                </div>
                <div class="panel-options">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-default">
                        <i class="entypo-left"></i> Back to User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">User Information</div>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin" {{ old('role', $user->role->value) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="farmer" {{ old('role', $user->role->value) == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="buyer" {{ old('role', $user->role->value) == 'buyer' ? 'selected' : '' }}>Buyer</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="business_name">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" value="{{ old('business_name', $user->business_name) }}">
                                @error('business_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="business_type">Business Type</label>
                                <input type="text" class="form-control" id="business_type" name="business_type" value="{{ old('business_type', $user->business_type) }}">
                                @error('business_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                Active User
                            </label>
                        </div>
                        @error('is_active')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="entypo-check"></i> Update User
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-default">
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
</style>
@endpush
