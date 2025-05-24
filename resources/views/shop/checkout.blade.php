@extends('layouts.shop')
@section('content')
<style>
    .checkout-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-label {
        font-weight: 500;
    }
    .order-summary {
        position: sticky;
        top: 20px;
    }
</style>

<header class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-success">Checkout</h1>
        <p class="lead">Complete your purchase</p>
    </div>
</header>

<div class="container py-5">
    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <!-- Checkout Forms -->
            <div class="col-lg-8">
                <!-- Shipping Information -->
                <div class="checkout-section">
                    <h3 class="mb-4">Shipping Information</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Street Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="zip" class="form-label">ZIP/Postal Code</label>
                            <input type="text" class="form-control" id="zip" name="zip" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm order-summary">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                        <hr>
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
                        <button type="submit" class="btn btn-success w-100">
                            Place Order
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-success w-100 mt-2">
                            Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add your form validation and submission logic here
    this.submit();
});
</script>
@endsection