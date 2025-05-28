@extends('layouts.shop')
@section('content')
<style>
    .cart-item {
        transition: transform 0.2s;
    }
    .cart-item:hover {
        transform: translateY(-2px);
    }
    .quantity-input {
        width: 70px;
    }
    .cart-summary {
        position: sticky;
        top: 20px;
    }
</style>

<header class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-success">Your Shopping Cart</h1>
        <p class="lead">Review your items and proceed to checkout</p>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            @if(count($cartItems) > 0)
                @foreach($cartItems as $item)
                    <div class="card mb-3 cart-item">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item['image'] }}" class="img-fluid rounded" alt="{{ $item['name'] }}">
                                </div>
                                <div class="col-md-4">
                                    <h5 class="card-title mb-1">{{ $item['name'] }}</h5>
                                    <p class="text-muted mb-0">₱{{ number_format($item['price'], 2) }}/{{ $item['unit'] }}</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $item['id'] }}, 'decrease')">-</button>
                                        <input type="number" class="form-control text-center quantity-input" value="{{ $item['quantity'] }}" min="1" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $item['id'] }}, 'increase')">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5 class="mb-0">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</h5>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">Add some items to your cart to see them here</p>
                    <!-- <a href="{{ route('shop.index') }}" class="btn btn-">Continue Shopping</a> -->
                </div>
            @endif
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm cart-summary">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>₱{{ number_format($shipping, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong class="text-success">₱{{ number_format($total, 2) }}</strong>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">
                        Proceed to Checkout
                    </a>
                    <!-- <a href="{{ route('shop.index') }}" class="btn btn-success w-100 mt-2">
                        Continue Shopping
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateQuantity(id, action) {
    const input = event.target.parentElement.querySelector('.quantity-input');
    let value = parseInt(input.value);

    if (action === 'increase') {
        value++;
    } else if (action === 'decrease' && value > 1) {
        value--;
    }

    input.value = value;

    // Send update to server
    fetch(`/cart/update/${id}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to update totals
            window.location.reload();
        }
    });
}
</script>
@endsection