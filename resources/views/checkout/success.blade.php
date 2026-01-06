@extends('layouts.shop')
@section('content')

<style>
    .success-container {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 15px;
        padding: 40px;
        margin: 40px 0;
        text-align: center;
    }
    .order-details {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 30px;
        margin: 30px 0;
    }
    .order-item {
        border-bottom: 1px solid #dee2e6;
        padding: 15px 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .farmer-info {
        background-color: #e8f5e8;
        border-left: 4px solid #28a745;
        padding: 15px;
        margin: 15px 0;
    }
</style>

<div class="container py-5">
    <div class="success-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <i class="fas fa-check-circle fa-5x mb-4"></i>
                <h1 class="display-4 fw-bold mb-3">
                    @if($order->is_negotiation)
                        Price Negotiation Request Submitted!
                    @else
                        Order Placed Successfully!
                    @endif
                </h1>
                <p class="lead mb-4">
                    @if($order->is_negotiation)
                        Thank you for supporting local farmers. Your price negotiation request has been submitted. The farmer will review your offer and notify you once they respond.
                    @else
                        Thank you for supporting local farmers. Your order has been confirmed.
                    @endif
                </p>
                @if($order->is_negotiation)
                    <div class="alert alert-warning mt-3" style="background-color: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-handshake me-2"></i>
                        <strong>Negotiation Status:</strong> Pending farmer review
                    </div>
                @endif
                <div class="row text-center">
                    <div class="col-md-4">
                        <h5><i class="fas fa-receipt me-2"></i>Order #{{ $order->id }}</h5>
                    </div>
                    <div class="col-md-4">
                        <h5><i class="fas fa-calendar me-2"></i>{{ $order->created_at->format('M d, Y') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <h5><i class="fas fa-money-bill-wave me-2"></i>₱{{ number_format($order->total, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Details -->
            <div class="order-details">
                <h3 class="mb-4"><i class="fas fa-box me-2"></i>Order Details</h3>

                @php
                    $groupedItems = $order->items->groupBy(function($item) {
                        return $item->product->user_id;
                    });
                @endphp

                @foreach($groupedItems as $farmerId => $farmerItems)
                    @php
                        $firstItem = $farmerItems->first();
                        $farmer = $firstItem->product->user;
                        $farmerSubtotal = $farmerItems->sum(function($item) {
                            return ($item->negotiated_price ?? $item->price) * $item->quantity;
                        });
                        $farmerOriginalSubtotal = $farmerItems->sum(function($item) {
                            return $item->price * $item->quantity;
                        });
                    @endphp

                    <div class="farmer-info">
                        <h5 class="text-success mb-2">
                            <i class="fas fa-store me-2"></i>
                            {{ $farmer->business_name ?? $farmer->name }}
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $farmer->city ? $farmer->city . ', ' . $farmer->state : 'Philippines' }}
                        </p>
                        <p class="text-info small mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ $farmerItems->count() }} item(s) from this farmer
                        </p>
                    </div>

                    @foreach($farmerItems as $item)
                        <div class="order-item">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item->product->getImageUrl() }}"
                                         alt="{{ $item->product->name }}"
                                         class="img-fluid rounded"
                                         style="max-height: 60px;">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $item->product->unit_type }}</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-success rounded-pill">{{ $item->quantity }}x</span>
                                </div>
                                <div class="col-md-2 text-end">
                                    @if($item->negotiated_price !== null)
                                        <div>
                                            <small class="text-muted text-decoration-line-through">₱{{ number_format($item->price * $item->quantity, 2) }}</small>
                                            <br>
                                            <strong class="text-success">₱{{ number_format($item->negotiated_price * $item->quantity, 2) }}</strong>
                                            <br>
                                            <small class="text-info">
                                                <i class="fas fa-handshake"></i> Negotiated
                                            </small>
                                        </div>
                                    @else
                                        <strong class="text-success">₱{{ number_format($item->price * $item->quantity, 2) }}</strong>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-2 mb-3">
                        <div class="col-md-10">
                            <small class="text-muted">Subtotal from {{ $farmer->business_name ?? $farmer->name }}:</small>
                        </div>
                        <div class="col-md-2 text-end">
                            @if($farmerSubtotal < $farmerOriginalSubtotal)
                                <div>
                                    <small class="text-muted text-decoration-line-through">₱{{ number_format($farmerOriginalSubtotal, 2) }}</small>
                                    <br>
                                    <strong class="text-success">₱{{ number_format($farmerSubtotal, 2) }}</strong>
                                    <br>
                                    <small class="text-danger">Saved: ₱{{ number_format($farmerOriginalSubtotal - $farmerSubtotal, 2) }}</small>
                                </div>
                            @else
                                <strong class="text-success">₱{{ number_format($farmerSubtotal, 2) }}</strong>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Delivery Information -->
            <div class="order-details">
                <h3 class="mb-4"><i class="fas fa-truck me-2"></i>Delivery Information</h3>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Delivery Option:</h6>
                        <p class="text-capitalize">
                            <i class="fas fa-{{ $order->delivery_option === 'pickup' ? 'hand-paper' : 'truck' }} me-2"></i>
                            {{ $order->delivery_option === 'pickup' ? 'Farm Pickup' : 'Home Delivery' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Payment Method:</h6>
                        <p class="text-capitalize">
                            <i class="fas fa-{{ $order->payment_method === 'cash' ? 'money-bill-wave' : 'mobile-alt' }} me-2"></i>
                            {{ $order->payment_method === 'cash' ? 'Cash on Delivery/Pickup' : 'GCash' }}
                        </p>
                    </div>
                </div>

                @if($order->delivery_option === 'delivery')
                    <div class="mt-3">
                        <h6>Delivery Address:</h6>
                        <p class="mb-0">
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                        </p>
                    </div>
                @endif

                @if($order->special_instructions)
                    <div class="mt-3">
                        <h6>Special Instructions:</h6>
                        <p class="mb-0">{{ $order->special_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="order-details">
                <h3 class="mb-4"><i class="fas fa-receipt me-2"></i>Order Summary</h3>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery:</span>
                    <span>{{ $order->shipping > 0 ? '₱' . number_format($order->shipping, 2) : 'Free' }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="text-success">₱{{ number_format($order->total, 2) }}</strong>
                </div>

                @if($order->is_negotiation)
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-handshake me-2"></i>Price Negotiation Status</h6>
                        <ul class="mb-0 small">
                            <li>Your price negotiation request has been submitted</li>
                            <li>The farmer will review your offer</li>
                            <li>You'll be notified when they accept or reject</li>
                            <li>If accepted, the order will be confirmed with negotiated prices</li>
                            <li>Track the status in your orders dashboard</li>
                        </ul>
                    </div>
                @else
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>What's Next?</h6>
                        <ul class="mb-0 small">
                            <li>You'll receive a confirmation email shortly</li>
                            <li>The farmer(s) will prepare your order</li>
                            <li>You'll be contacted for pickup/delivery details</li>
                            <li>Track your order in your dashboard</li>
                        </ul>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('buyer.orders') }}" class="btn btn-success btn-lg w-100 mb-2">
                        <i class="fas fa-list me-2"></i>View All Orders
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

