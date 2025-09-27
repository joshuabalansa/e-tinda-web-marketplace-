@extends('layouts.admin')
@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>User Details: {{ $user->name }}</h4>
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

<!-- User Statistics -->
<div class="row">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-primary">{{ $stats['total_products'] }}</h3>
                <p class="text-muted">Products</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-success">{{ $stats['total_orders'] }}</h3>
                <p class="text-muted">Orders</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-info">{{ $stats['total_reviews'] }}</h3>
                <p class="text-muted">Reviews</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-warning">{{ $stats['total_wishlist'] }}</h3>
                <p class="text-muted">Wishlist Items</p>
            </div>
        </div>
    </div>
</div>

<!-- User Information -->
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Basic Information</div>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td>
                            <span class="label label-{{ $user->role->value === 'admin' ? 'danger' : ($user->role->value === 'farmer' ? 'success' : 'info') }}">
                                {{ ucfirst($user->role->value) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($user->is_active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-warning">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $user->phone ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>{{ $user->address ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Joined:</strong></td>
                        <td>{{ $user->created_at->format('F d, Y \a\t g:i A') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td>{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Business Information</div>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <td><strong>Business Name:</strong></td>
                        <td>{{ $user->business_name ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Business Type:</strong></td>
                        <td>{{ $user->business_type ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Business Description:</strong></td>
                        <td>{{ $user->business_description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Farm Address:</strong></td>
                        <td>{{ $user->farm_address ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>City:</strong></td>
                        <td>{{ $user->city ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>State:</strong></td>
                        <td>{{ $user->state ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Zip Code:</strong></td>
                        <td>{{ $user->zip_code ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Country:</strong></td>
                        <td>{{ $user->country ?: '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Actions</div>
            </div>
            <div class="panel-body">
                <div class="btn-group">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="entypo-pencil"></i> Edit User
                    </a>

                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                    onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                                <i class="entypo-{{ $user->is_active ? 'block' : 'check' }}"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone!')">
                                <i class="entypo-trash"></i> Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
@if($user->products->count() > 0 || $user->orders->count() > 0)
<div class="row">
    <div class="col-md-6">
        @if($user->products->count() > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Recent Products</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->products->take(5) as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price_per_unit, 2) }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    <span class="label label-{{ $product->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-6">
        @if($user->orders->count() > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Recent Orders</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->take(5) as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="label label-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .panel {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .btn-group .btn {
        margin-right: 10px;
    }
</style>
@endpush
