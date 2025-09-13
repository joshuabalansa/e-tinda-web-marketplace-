@extends('layouts.shop')

@section('content')
<!-- Order Details Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 text-success fw-bold">Order Details</h1>
                <p class="lead mb-0">Order #{{ $order->id }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('buyer.dashboard') }}" class="btn btn-outline-success me-2">
                    <i class="fas fa-home me-2"></i> Back to Dashboard
                </a>
                <a href="{{ route('buyer.orders') }}" class="btn btn-outline-success">
                    <i class="fas fa-arrow-left me-2"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container my-5">
        <div class="row">
            <!-- Order Information -->
            <div class="col-lg-8">
                <div class="card border-success mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i> Order Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success fw-bold mb-3">Order Details</h6>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-hashtag text-success me-2"></i>
                                        <strong>Order ID:</strong> #{{ $order->id }}
                                    </p>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-calendar text-success me-2"></i>
                                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-clock text-success me-2"></i>
                                        <strong>Order Time:</strong> {{ $order->created_at->format('h:i A') }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle text-success me-2"></i>
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }} rounded-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success fw-bold mb-3">Shipping Information</h6>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-user text-success me-2"></i>
                                        <strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}
                                    </p>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-envelope text-success me-2"></i>
                                        <strong>Email:</strong> {{ $order->email }}
                                    </p>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <strong>Phone:</strong> {{ $order->phone }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        <strong>Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Order Items -->
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-bag me-2"></i> Order Items
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            @if($item->product && $item->product->image_url)
                                <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                     alt="{{ $item->product->name }}"
                                     class="img-fluid rounded"
                                     style="max-width: 80px; height: auto;">
                            @else
                                <div class="bg-light text-center py-3 rounded" style="width: 80px; height: 60px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-1">{{ $item->product->name ?? 'Product Unavailable' }}</h6>
                            <p class="text-muted small mb-0">
                                @if($item->product)
                                    <i class="fas fa-store text-success me-1"></i>
                                    Sold by: {{ $item->product->user->name ?? 'Unknown Seller' }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge bg-success rounded-pill">{{ $item->quantity }}x</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <strong class="text-success">₱{{ number_format($item->price, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i> Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-bold">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Shipping:</span>
                            <span class="fw-bold">₱{{ number_format($order->shipping, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h6 text-success fw-bold mb-0">Total:</span>
                            <span class="h5 text-success fw-bold">₱{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('shop.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-shopping-cart me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
