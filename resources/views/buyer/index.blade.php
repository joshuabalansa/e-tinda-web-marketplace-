@extends('layouts.app')
@section('content')

<div class="main-content py-5">
    <div class="container">
        <!-- Welcome Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-center position-relative">
                    <h1 class="section-title mb-3">{{ __('dashboard.welcome_message') }}, {{ ucwords(Auth::user()->name) }}!</h1>
                    <p class="lead text-muted">{{ __('dashboard.manage_shopping_experience') }}</p>

                    <!-- Language Switcher -->
                    <div class="position-absolute" style="top: 0; right: 0;">
                        @include('components.language-switcher')
                    </div>
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
                        <h5 class="card-title fw-bold">{{ __('dashboard.my_orders') }}</h5>
                        <p class="card-text text-muted">{{ __('dashboard.track_orders') }}</p>
                        <a href="{{ route('buyer.orders') }}" class="btn btn-success w-100">
                            <i class="fas fa-arrow-right me-2"></i>{{ __('dashboard.view_orders') }}
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
                        <h5 class="card-title fw-bold">{{ __('dashboard.my_wishlist') }}</h5>
                        <p class="card-text text-muted">{{ __('dashboard.save_products') }}</p>
                        <a href="{{ route('buyer.wishlist') }}" class="btn btn-danger w-100">
                            <i class="fas fa-arrow-right me-2"></i>{{ __('dashboard.view_wishlist') }}
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
                        <h5 class="card-title fw-bold">{{ __('dashboard.shopping_cart') }}</h5>
                        <p class="card-text text-muted">{{ __('dashboard.review_cart_items') }}</p>
                        <a href="{{ route('cart.index') }}" class="btn btn-warning w-100 text-dark">
                            <i class="fas fa-arrow-right me-2"></i>{{ __('dashboard.view_cart') }}
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
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>{{ __('dashboard.quick_actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 justify-content-center">
                            <div class="col-md-6 col-lg-3">
                                <a href="/shop" class="btn btn-outline-success w-100">
                                    <i class="fas fa-store me-2"></i>{{ __('dashboard.continue_shopping') }}
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="/categories" class="btn btn-outline-success w-100">
                                    <i class="fas fa-tags me-2"></i>{{ __('dashboard.browse_categories') }}
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="/forums" class="btn btn-outline-success w-100">
                                    <i class="fas fa-comments me-2"></i>{{ __('dashboard.visit_forums') }}
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
                        <h5 class="mb-0"><i class="fas fa-truck me-2"></i>{{ __('dashboard.shipping_status_tracking') }}</h5>
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
                                                            +{{ $order->items->count() - 1 }} {{ __('dashboard.more_items') }}
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-warning">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        {{ __('dashboard.order_pending') }}
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'processing')
                                                <i class="fas fa-cog text-info me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Processing</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} {{ __('dashboard.more_items') }}
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-info">
                                                        <i class="fas fa-box me-1"></i>
                                                        {{ __('dashboard.order_processing') }}
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'shipped')
                                                <i class="fas fa-shipping-fast text-primary me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Shipped</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} {{ __('dashboard.more_items') }}
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-primary">
                                                        <i class="fas fa-route me-1"></i>
                                                        {{ __('dashboard.order_shipped') }}
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'delivered')
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Delivered</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} {{ __('dashboard.more_items') }}
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-success">
                                                        <i class="fas fa-home me-1"></i>
                                                        {{ __('dashboard.order_delivered') }} {{ $order->updated_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            @elseif($order->status === 'cancelled')
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - Cancelled</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} {{ __('dashboard.more_items') }}
                                                        @endif
                                                    @else
                                                        Order items
                                                    @endif
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-danger">
                                                        <i class="fas fa-ban me-1"></i>
                                                        {{ __('dashboard.order_cancelled') }}
                                                    </small>
                                                </div>
                                            @else
                                                <i class="fas fa-question-circle text-secondary me-2"></i>
                                                <span class="fw-medium">Order #{{ $order->id }} - {{ ucfirst($order->status) }}</span>
                                                <small class="text-muted d-block">
                                                    @if($order->items->count() > 0)
                                                        {{ $order->items->first()->product->name ?? 'Product' }}
                                                        @if($order->items->count() > 1)
                                                            +{{ $order->items->count() - 1 }} {{ __('dashboard.more_items') }}
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
                                                <i class="fas fa-eye me-1"></i> {{ __('dashboard.view_order') }}
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
                                        {{ __('dashboard.no_orders_yet') }}
                                    @else
                                        {{ __('dashboard.loading_orders') }}
                                    @endif
                                </h6>
                                <p class="text-muted small">
                                    @if(isset($orders))
                                        {{ __('dashboard.start_shopping_message') }}
                                    @else
                                        {{ __('dashboard.wait_loading_message') }}
                                    @endif
                                </p>
                                @if(isset($orders))
                                    <a href="{{ route('shop.index') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-shopping-cart me-1"></i> {{ __('dashboard.start_shopping') }}
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
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>{{ __('dashboard.quick_stats') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <h4 class="text-success fw-bold">{{ isset($orders) ? $orders->count() : 0 }}</h4>
                                    <small class="text-muted">{{ __('dashboard.total_orders') }}</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-info fw-bold">{{ isset($wishlistItems) ? $wishlistItems : 0 }}</h4>
                                <small class="text-muted">{{ __('dashboard.wishlist_items') }}</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning fw-bold">{{ isset($cartItems) ? $cartItems : 0 }}</h4>
                                <small class="text-muted">{{ __('dashboard.cart_items') }}</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-danger fw-bold">{{ isset($reviews) ? $reviews : 0 }}</h4>
                                <small class="text-muted">{{ __('dashboard.reviews_given') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
