@extends('layouts.app')
@section('content')

<div class="main-content py-5">
    <div class="container">
        <!-- Welcome Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-center">
                    <h1 class="section-title mb-3">Welcome, {{ ucwords(Auth::user()->name) }}!</h1>
                    <p class="lead text-muted">Manage your shopping experience and track your orders</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Navigation Cards -->
        <div class="row g-4 mb-5">
            <!-- Orders Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 category-card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shopping-bag fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">My Orders</h5>
                        <p class="card-text text-muted">Track your current orders and view order history</p>
                        <a href="{{ route('buyer.orders') }}" class="btn btn-success w-100">
                            <i class="fas fa-arrow-right me-2"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>



            <!-- Wishlist Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 category-card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-heart fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title fw-bold">My Wishlist</h5>
                        <p class="card-text text-muted">Save products you love for later purchase</p>
                        <a href="{{ route('buyer.wishlist') }}" class="btn btn-danger w-100">
                            <i class="fas fa-arrow-right me-2"></i>View Wishlist
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cart Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 category-card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold">Shopping Cart</h5>
                        <p class="card-text text-muted">Review items in your cart and proceed to checkout</p>
                        <a href="{{ route('cart.index') }}" class="btn btn-warning w-100 text-dark">
                            <i class="fas fa-arrow-right me-2"></i>View Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <a href="/shop" class="btn btn-outline-success w-100">
                                    <i class="fas fa-store me-2"></i>Continue Shopping
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="/categories" class="btn btn-outline-success w-100">
                                    <i class="fas fa-tags me-2"></i>Browse Categories
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="/forums" class="btn btn-outline-success w-100">
                                    <i class="fas fa-comments me-2"></i>Visit Forums
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="/profile" class="btn btn-outline-success w-100">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Status & Tracking Section -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Shipping Status & Tracking</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($orders) && $orders && $orders->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($orders->take(4) as $order)
                                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                        <div>
                                            @if($order->status === 'pending')
                                                <i class="fas fa-clock text-warning me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Pending</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} more items
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-warning">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        Order is being processed
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'processing')
                                                <i class="fas fa-cog text-info me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Processing</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} more items
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-info">
                                                        <i class="fas fa-box me-1"></i>
                                                        Order is being prepared for shipping
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'shipped')
                                                <i class="fas fa-shipping-fast text-primary me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Shipped</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} more items
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-primary">
                                                        <i class="fas fa-route me-1"></i>
                                                        Package is on its way
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'delivered')
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Delivered</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} more items
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-success">
                                                        <i class="fas fa-home me-1"></i>
                                                        Delivered on {{ $order->updated_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'cancelled')
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Cancelled</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} more items
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-danger">
                                                        <i class="fas fa-ban me-1"></i>
                                                        Order was cancelled
                                                    </small>
                                                </div>
                                            @else
                                                <i class="fas fa-question-circle text-secondary me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - {{ ucfirst($order->status) }}</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} more items
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-secondary">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Status: {{ ucfirst($order->status) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'processing' ? 'info' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'secondary')))) }} mb-2">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-eye me-1"></i> View Order
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-truck text-muted mb-3" style="font-size: 3rem;"></i>
                                <h6 class="text-muted">
                                    @if(isset($orders))
                                        No Orders Yet
                                    @else
                                        Loading Orders...
                                    @endif
                                </h6>
                                <p class="text-muted small">
                                    @if(isset($orders))
                                        Start shopping to see your order tracking here
                                    @else
                                        Please wait while we load your order information
                                    @endif
                                </p>
                                @if(isset($orders))
                                    <a href="{{ route('shop.index') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-shopping-cart me-1"></i> Start Shopping
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <h4 class="text-success fw-bold">{{ isset($orders) ? $orders->count() : 0 }}</h4>
                                    <small class="text-muted">Total Orders</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-info fw-bold">{{ isset($wishlistItems) ? $wishlistItems : 0 }}</h4>
                                <small class="text-muted">Wishlist Items</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning fw-bold">{{ isset($cartItems) ? $cartItems : 0 }}</h4>
                                <small class="text-muted">Cart Items</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-danger fw-bold">{{ isset($reviews) ? $reviews : 0 }}</h4>
                                <small class="text-muted">Reviews Given</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
