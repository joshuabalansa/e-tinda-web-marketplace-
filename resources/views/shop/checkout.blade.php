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
    .farmer-info-card {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .payment-method-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method-card:hover {
        border-color: #28a745;
        background-color: #f8f9fa;
    }
    .payment-method-card.selected {
        border-color: #28a745;
        background-color: #e8f5e8;
    }
    .delivery-options {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .farmer-contact-info {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 15px;
        margin-bottom: 20px;
    }
    .product-farmer-info {
        background-color: #f0f8ff;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>

<header class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-success">
            <i class="fas fa-shopping-cart"></i> {{ __('shop.checkout') }}
        </h1>
        <p class="lead">{{ __('shop.complete_purchase') }}</p>
    </div>
</header>

<div class="container py-5">
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Farmer Information Display -->
    <div class="farmer-info-card">
        <h4><i class="fas fa-seedling"></i> Direct from Local Farmers</h4>
        <p class="mb-0">Supporting local agriculture and getting fresh, quality produce directly from Filipino farmers.</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Items with Farmer Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-box"></i> Order Items
                    </h5>

                    @foreach($items as $item)
                        <div class="product-farmer-info">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="max-height: 60px;">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> Farmer: {{ $item['farmer_name'] ?? 'Local Farmer' }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $item['farmer_location'] ?? 'Philippines' }}
                                    </small>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge badge-primary">{{ $item['quantity'] }} {{ $item['unit'] }}</span>
                                </div>
                                <div class="col-md-2 text-right">
                                    <strong>₱{{ number_format($item['subtotal'], 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-truck"></i> Delivery Information
                    </h5>

                    <div class="delivery-options">
                        <h6><i class="fas fa-info-circle"></i> Delivery Options</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="pickup" value="pickup" checked>
                            <label class="form-check-label" for="pickup">
                                <strong>Farm Pickup</strong> - Pick up directly from the farm (Free)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="delivery" value="delivery">
                            <label class="form-check-label" for="delivery">
                                <strong>Home Delivery</strong> - Delivered to your address (₱{{ $shippingCost ?? 100 }})
                            </label>
                        </div>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="delivery_option" id="delivery_option_input" value="pickup">
                        <input type="hidden" name="shipping" id="shipping_input" value="0">

                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">{{ __('shop.first_name') }}</label>
                                <input type="text"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name"
                                       name="first_name"
                                       value="{{ old('first_name', auth()->user()->name ? explode(' ', auth()->user()->name)[0] : '') }}"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">{{ __('shop.last_name') }}</label>
                                <input type="text"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name"
                                       name="last_name"
                                       value="{{ old('last_name', auth()->user()->name ? (count(explode(' ', auth()->user()->name)) > 1 ? implode(' ', array_slice(explode(' ', auth()->user()->name), 1)) : '') : '') }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('shop.email') }}</label>
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
                                <label for="phone" class="form-label">{{ __('shop.phone') }}</label>
                                <input type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       placeholder="+63 9XX XXX XXXX"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information (only show for delivery) -->
                        <div id="address_section" style="display: none;">
                            <div class="mb-3">
                                <label for="address" class="form-label">Delivery Address</label>
                                <input type="text"
                                       class="form-control @error('address') is-invalid @enderror"
                                       id="address"
                                       name="address"
                                       value="{{ old('address', auth()->user()->address ?? '') }}"
                                       placeholder="Street address, Barangay">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City/Municipality</label>
                                    <input type="text"
                                           class="form-control @error('city') is-invalid @enderror"
                                           id="city"
                                           name="city"
                                           value="{{ old('city', auth()->user()->city ?? '') }}"
                                           placeholder="e.g., Quezon City">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">Province</label>
                                    <input type="text"
                                           class="form-control @error('state') is-invalid @enderror"
                                           id="state"
                                           name="state"
                                           value="{{ old('state', auth()->user()->state ?? '') }}"
                                           placeholder="e.g., Metro Manila">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="zip" class="form-label">ZIP Code</label>
                                    <input type="text"
                                           class="form-control @error('zip') is-invalid @enderror"
                                           id="zip"
                                           name="zip"
                                           value="{{ old('zip', auth()->user()->zip_code ?? '') }}"
                                           placeholder="e.g., 1100">
                                    @error('zip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <h6><i class="fas fa-credit-card"></i> Payment Method</h6>
                            <div class="payment-method-card selected" data-method="cash">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                                    <label class="form-check-label" for="cash">
                                        <strong><i class="fas fa-money-bill-wave"></i> Cash on Delivery/Pickup</strong>
                                        <br><small class="text-muted">Pay when you receive your order</small>
                                    </label>
                                </div>
                            </div>
                            <div class="payment-method-card" data-method="gcash">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="gcash">
                                    <label class="form-check-label" for="gcash">
                                        <strong><i class="fas fa-mobile-alt"></i> GCash</strong>
                                        <br><small class="text-muted">Pay via GCash mobile payment</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <label for="special_instructions" class="form-label">Special Instructions</label>
                            <textarea class="form-control"
                                      id="special_instructions"
                                      name="special_instructions"
                                      rows="3"
                                      placeholder="Any special requests or instructions for the farmer...">{{ old('special_instructions') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check"></i> Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card order-summary">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-receipt"></i> Order Summary
                    </h5>

                    @foreach($items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <small>{{ $item['name'] }}</small>
                                <br><small class="text-muted">{{ $item['quantity'] }} {{ $item['unit'] }}</small>
                            </div>
                            <span>₱{{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery</span>
                        <span id="shipping-display">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total</strong>
                        <strong id="total-display">₱{{ number_format($total, 2) }}</strong>
                    </div>

                    <!-- Farmer Contact Info -->
                    <div class="farmer-contact-info mt-3">
                        <h6><i class="fas fa-phone"></i> Need Help?</h6>
                        <p class="mb-1">Contact the farmers directly for questions about your order.</p>
                        <small class="text-muted">Supporting local agriculture since 2024</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryOptions = document.querySelectorAll('input[name="delivery_option"]');
    const addressSection = document.getElementById('address_section');
    const shippingInput = document.getElementById('shipping_input');
    const shippingDisplay = document.getElementById('shipping-display');
    const totalDisplay = document.getElementById('total-display');
    const subtotal = {{ $total }};
    const shippingCost = {{ $shippingCost ?? 100 }};

    // Payment method selection
    const paymentCards = document.querySelectorAll('.payment-method-card');
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            paymentCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });

    // Delivery option handling
    deliveryOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'delivery') {
                addressSection.style.display = 'block';
                shippingInput.value = shippingCost;
                shippingDisplay.textContent = '₱' + shippingCost.toFixed(2);
                updateTotal();
            } else {
                addressSection.style.display = 'none';
                shippingInput.value = 0;
                shippingDisplay.textContent = 'Free';
                updateTotal();
            }
        });
    });

    function updateTotal() {
        const shipping = parseFloat(shippingInput.value) || 0;
        const total = subtotal + shipping;
        totalDisplay.textContent = '₱' + total.toFixed(2);
    }

    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;

        if (deliveryOption === 'delivery') {
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const state = document.getElementById('state').value;
            const zip = document.getElementById('zip').value;

            if (!address || !city || !state || !zip) {
                e.preventDefault();
                alert('Please fill in all delivery address fields.');
                return false;
            }
        }
    });
});
</script>
@endsection