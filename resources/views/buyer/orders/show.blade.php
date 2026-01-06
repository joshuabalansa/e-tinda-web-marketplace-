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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
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
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} rounded-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @if($order->is_negotiation)
                                            <span class="badge bg-warning rounded-pill ms-2">
                                                <i class="fas fa-handshake me-1"></i> Negotiation
                                            </span>
                                        @endif
                                    </p>
                                    @if($order->is_negotiation && $order->status === 'pending')
                                        <div class="alert alert-info mt-3 mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Price Negotiation Pending:</strong> Your price offer is being reviewed by the farmer. You will be notified once they respond.
                                        </div>
                                    @endif
                                    @if($order->status === 'pending')
                                        <div class="mt-3">
                                            <form action="{{ route('buyer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order? The stock will be restored to inventory.');">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times-circle me-2"></i> Cancel Order
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success fw-bold mb-3">Delivery Information</h6>
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
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-truck text-success me-2"></i>
                                        <strong>Delivery Method:</strong>
                                        <span class="badge bg-{{ $order->delivery_option === 'pickup' ? 'info' : 'success' }}">
                                            {{ ucfirst($order->delivery_option) }}
                                        </span>
                                    </p>
                                    @if($order->delivery_option === 'delivery')
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            <strong>Delivery Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                                        </p>
                                    @else
                                        <p class="text-info mb-0">
                                            <i class="fas fa-info-circle text-success me-2"></i>
                                            <strong>Pickup:</strong> You will collect items from each farmer's location (see details below)
                                        </p>
                                    @endif
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
                    @php
                        $groupedItems = $order->items->groupBy(function($item) {
                            return $item->product ? $item->product->user_id : 'unknown';
                        });
                    @endphp

                    @foreach($groupedItems as $farmerId => $items)
                        @php
                            $farmer = $items->first()->product->user ?? null;
                        @endphp

                        <!-- Farmer Section -->
                        <div class="farmer-section mb-4 p-3 bg-light rounded">
                            <div class="row align-items-center mb-3">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-success mb-1">
                                        <i class="fas fa-store me-2"></i>
                                        {{ $farmer ? $farmer->business_name ?? $farmer->name : 'Unknown Seller' }}
                                    </h6>
                                    @if($farmer)
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $farmer->city ?? 'Farm Location' }}, {{ $farmer->state ?? 'Farm Province' }}
                                        </p>
                                        @if($order->delivery_option === 'pickup')
                                            <p class="text-info small mb-0">
                                                <i class="fas fa-home me-1"></i>
                                                Pickup from: {{ $farmer->farm_address ?? 'Farm Location' }}
                                            </p>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-success">{{ $items->count() }} item(s)</span>
                                </div>
                            </div>

                            <!-- Items from this farmer -->
                            @foreach($items as $item)
                            <div class="row mb-2 pb-2 border-bottom border-light">
                                <div class="col-md-2">
                                    @if($item->product)
                                        <img src="{{ $item->product->getImageUrl() }}"
                                             alt="{{ $item->product->name }}"
                                             class="img-fluid rounded"
                                             style="max-width: 60px; height: auto;">
                                    @else
                                        <div class="bg-light text-center py-2 rounded" style="width: 60px; height: 45px;">
                                            <i class="fas fa-image text-muted" style="font-size: 0.8rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $item->product->name ?? 'Product Unavailable' }}</h6>
                                    <p class="text-muted small mb-0">{{ $item->product->description ?? '' }}</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-success rounded-pill" style="font-size: 0.8rem;">{{ $item->quantity }}x</span>
                                </div>
                                <div class="col-md-2 text-end">
                                    @if($item->negotiated_price !== null)
                                        <div>
                                            <small class="text-muted text-decoration-line-through">₱{{ number_format($item->price, 2) }}</small>
                                            <br>
                                            <strong class="text-success" style="font-size: 0.9rem;">₱{{ number_format($item->negotiated_price, 2) }}</strong>
                                            <br>
                                            <small class="text-info">Negotiated</small>
                                        </div>
                                    @else
                                        <strong class="text-success" style="font-size: 0.9rem;">₱{{ number_format($item->price, 2) }}</strong>
                                    @endif
                                </div>
                            </div>
                            @endforeach

                            <!-- Farmer subtotal -->
                            <div class="row mt-2">
                                <div class="col-md-10">
                                    <small class="text-muted">Subtotal from this farmer:</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    @php
                                        $farmerSubtotal = $items->sum(function($item) {
                                            return ($item->negotiated_price ?? $item->price) * $item->quantity;
                                        });
                                        $originalSubtotal = $items->sum(function($item) {
                                            return $item->price * $item->quantity;
                                        });
                                    @endphp
                                    @if($farmerSubtotal < $originalSubtotal)
                                        <div>
                                            <small class="text-muted text-decoration-line-through">₱{{ number_format($originalSubtotal, 2) }}</small>
                                            <br>
                                            <strong class="text-success">₱{{ number_format($farmerSubtotal, 2) }}</strong>
                                        </div>
                                    @else
                                        <strong class="text-success">₱{{ number_format($farmerSubtotal, 2) }}</strong>
                                    @endif
                                </div>
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
                        @if($order->status === 'pending')
                            <form action="{{ route('buyer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order? The stock will be restored to inventory.');" class="mb-3">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times-circle me-2"></i> Cancel Order
                                </button>
                            </form>
                        @endif

                        @if($order->status === 'delivered' && !$order->isReceived())
                            <form action="{{ route('buyer.orders.received', $order->id) }}" method="POST" class="mb-3">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check-circle me-2"></i> Mark as Received
                                </button>
                            </form>
                        @endif

                        @if($order->status === 'delivered' && $order->isReceived())
                            @php
                                $reviewedProductIds = $order->reviews->pluck('product_id')->toArray();
                                $hasUnreviewedProducts = $order->items->filter(function($item) use ($reviewedProductIds) {
                                    return $item->product && !in_array($item->product->id, $reviewedProductIds);
                                })->count() > 0;
                            @endphp

                            @if($hasUnreviewedProducts)
                                <a href="{{ route('buyer.orders.review', $order->id) }}" class="btn btn-warning w-100 mb-3">
                                    <i class="fas fa-star me-2"></i> Leave Review
                                </a>
                            @else
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i> All products have been reviewed
                                </div>
                            @endif
                        @endif

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
