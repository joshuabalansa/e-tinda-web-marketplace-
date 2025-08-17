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
    <h1 class="mb-4">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Shipping Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Shipping Information</h5>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name"
                                       name="first_name"
                                       value="{{ old('first_name') }}"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name"
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', auth()->user()->email ?? '') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address</label>
                            <input type="text"
                                   class="form-control @error('address') is-invalid @enderror"
                                   id="address"
                                   name="address"
                                   value="{{ old('address') }}"
                                   required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text"
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city"
                                       name="city"
                                       value="{{ old('city') }}"
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text"
                                       class="form-control @error('state') is-invalid @enderror"
                                       id="state"
                                       name="state"
                                       value="{{ old('state') }}"
                                       required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="zip" class="form-label">ZIP/Postal Code</label>
                                <input type="text"
                                       class="form-control @error('zip') is-invalid @enderror"
                                       id="zip"
                                       name="zip"
                                       value="{{ old('zip') }}"
                                       required>
                                @error('zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Shipping Cost -->
                        <div class="mb-4">
                            <label for="shipping" class="form-label">Shipping Cost (₱)</label>
                            <input type="number"
                                   class="form-control @error('shipping') is-invalid @enderror"
                                   id="shipping"
                                   name="shipping"
                                   value="{{ old('shipping', 100) }}"
                                   min="0"
                                   step="0.01"
                                   required>
                            @error('shipping')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card order-summary">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>

                    @foreach($items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item['name'] }} ({{ $item['quantity'] }} {{ $item['unit'] }})</span>
                            <span>₱{{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span id="shipping-display">₱100.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total</strong>
                        <strong id="total-display">₱{{ number_format($total + 100, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shippingInput = document.getElementById('shipping');
    const shippingDisplay = document.getElementById('shipping-display');
    const totalDisplay = document.getElementById('total-display');
    const subtotal = {{ $total }};

    function updateTotals() {
        const shipping = parseFloat(shippingInput.value) || 0;
        const total = subtotal + shipping;

        shippingDisplay.textContent = '₱' + shipping.toFixed(2);
        totalDisplay.textContent = '₱' + total.toFixed(2);
    }

    shippingInput.addEventListener('input', updateTotals);
    shippingInput.addEventListener('change', updateTotals);
});
</script>
@endsection