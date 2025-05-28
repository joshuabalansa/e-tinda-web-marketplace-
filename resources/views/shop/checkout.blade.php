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
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                                    id="shipping_address"
                                    name="shipping_address"
                                    rows="3"
                                    required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text"
                                   class="form-control @error('contact_number') is-invalid @enderror"
                                   id="contact_number"
                                   name="contact_number"
                                   value="{{ old('contact_number') }}"
                                   required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="gcash">
                                <label class="form-check-label" for="gcash">
                                    GCash
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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
            <div class="card">
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
                        <strong>Total</strong>
                        <strong>₱{{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add your form validation and submission logic here
    this.submit();
});
</script>
@endsection