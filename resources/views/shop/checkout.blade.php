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
    .farmer-section {
        border-left: 4px solid #28a745;
        background-color: #f8f9fa;
    }
    .vendor-summary {
        border-left: 3px solid #17a2b8;
        background-color: #f8f9fa;
    }
    .multi-vendor-alert {
        border-left: 4px solid #17a2b8;
    }
    /* Product Image Styles */
    .product-image-container {
        width: 60px;
        height: 60px;
        overflow: hidden;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .order-summary {
            position: relative;
            top: 0;
            margin-top: 20px;
        }
        .product-image-container {
            width: 50px;
            height: 50px;
        }
        .farmer-info-card {
            padding: 15px;
        }
        .farmer-info-card h4 {
            font-size: 1.2rem;
        }
        .card-body {
            padding: 15px;
        }
        .delivery-options {
            padding: 12px;
        }
        .payment-method-card {
            padding: 12px;
        }
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
                        @php
                            $farmerCount = collect($items)->groupBy('farmer_id')->count();
                        @endphp
                        @if($farmerCount > 1)
                            <span class="badge bg-info ms-2">{{ $farmerCount }} Vendors</span>
                        @endif
                    </h5>

                    @php
                        $groupedItems = collect($items)->groupBy('farmer_id');
                    @endphp

                    @foreach($groupedItems as $farmerId => $farmerItems)
                        @php
                            $firstItem = $farmerItems->first();
                            $farmerSubtotal = $farmerItems->sum('subtotal');
                        @endphp

                        <!-- Farmer Section -->
                        <div class="farmer-section mb-4 p-3 bg-light rounded">
                            <div class="row align-items-center mb-3">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-success mb-1">
                                        <i class="fas fa-store me-2"></i>
                                        {{ $firstItem['farmer_name'] ?? 'Local Farmer' }}
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $firstItem['farmer_location'] ?? 'Philippines' }}
                                    </p>
                                    <p class="text-info small mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ $farmerItems->count() }} item(s) from this farmer
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-success">{{ $farmerItems->count() }} item(s)</span>
                                </div>
                            </div>

                            <!-- Items from this farmer -->
                            @foreach($farmerItems as $item)
                            <div class="row mb-2 pb-2 border-bottom border-light align-items-center">
                                <div class="col-md-2">
                                    <div class="product-image-container">
                                        @if(!empty($item['image']))
                                            <img src="{{ $item['image'] }}"
                                                 alt="{{ $item['name'] }}"
                                                 class="img-fluid"
                                                 onerror="this.onerror=null; this.src='https://placehold.co/60x60?text={{ urlencode(substr($item['name'], 0, 10)) }}'; this.style.objectFit='cover';">
                                        @else
                                            <i class="fas fa-image text-muted" style="font-size: 1.5rem;"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1" style="font-size: 0.9rem;">{{ $item['name'] }}</h6>
                                    <p class="text-muted small mb-0">{{ $item['unit'] }}</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-success rounded-pill" style="font-size: 0.8rem;">{{ $item['quantity'] }}x</span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong class="text-success" style="font-size: 0.9rem;">₱{{ number_format($item['subtotal'], 2) }}</strong>
                                </div>
                            </div>
                            @endforeach

                            <!-- Farmer subtotal -->
                            <div class="row mt-2">
                                <div class="col-md-10">
                                    <small class="text-muted">Subtotal from {{ $firstItem['farmer_name'] ?? 'this farmer' }}:</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong class="text-success">₱{{ number_format($farmerSubtotal, 2) }}</strong>
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
                        @if($farmerCount > 1)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Multi-Vendor Order:</strong> Your order contains items from {{ $farmerCount }} different farmers.
                                Choose your preferred delivery method below.
                            </div>
                        @endif

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="pickup" value="pickup" checked>
                            <label class="form-check-label" for="pickup">
                                <strong>Farm Pickup</strong> - Pick up directly from each farmer's location (Free)
                                @if($farmerCount > 1)
                                    <br><small class="text-muted">You'll collect items from {{ $farmerCount }} different locations</small>
                                @endif
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="delivery" value="delivery">
                            <label class="form-check-label" for="delivery">
                                <strong>Home Delivery</strong> - Delivered to your address
                                <span id="delivery-fee-display">(Calculating...)</span>
                                @if($farmerCount > 1)
                                    <br><small class="text-muted">Items from all farmers will be delivered together</small>
                                @endif
                                <br><small class="text-info" id="delivery-info">Enter your address to calculate delivery fee</small>
                            </label>
                        </div>
                    </div>

                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
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

                        <!-- Price Negotiation Section -->
                        <div class="card mb-4" style="border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="is_negotiation" value="0">
                                    <input class="form-check-input" type="checkbox" id="enable_negotiation" name="is_negotiation" value="1">
                                    <label class="form-check-label" for="enable_negotiation">
                                        <strong><i class="fas fa-handshake me-2"></i>Request Price Negotiation</strong>
                                        <br><small class="text-muted">Propose your price and let the farmer review your offer</small>
                                    </label>
                                </div>

                                <div id="negotiation_section" style="display: none;">
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>How it works:</strong> Enter your proposed price for each item. The farmer will review your offer and can accept or reject it. Your order will only be confirmed after the farmer accepts.
                                    </div>

                                    @foreach($groupedItems as $farmerId => $farmerItems)
                                        @php
                                            $firstItem = $farmerItems->first();
                                        @endphp
                                        <div class="farmer-section mb-3 p-3 bg-light rounded">
                                            <h6 class="fw-bold text-success mb-3">
                                                <i class="fas fa-store me-2"></i>
                                                {{ $firstItem['farmer_name'] ?? 'Local Farmer' }}
                                            </h6>
                                            @foreach($farmerItems as $item)
                                            <div class="row mb-3 align-items-center border-bottom pb-3">
                                                <div class="col-md-4">
                                                    <strong>{{ $item['name'] }}</strong>
                                                    <br><small class="text-muted">{{ $item['quantity'] }} {{ $item['unit'] }}</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">Original Price:</label>
                                                    <div class="text-muted">₱{{ number_format($item['price'], 2) }}/{{ $item['unit'] }}</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="negotiated_price_{{ $item['id'] }}" class="form-label small">Your Offer:</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number"
                                                               class="form-control negotiated-price-input"
                                                               id="negotiated_price_{{ $item['id'] }}"
                                                               name="negotiated_prices[{{ $item['id'] }}]"
                                                               step="0.01"
                                                               min="0"
                                                               max="{{ $item['price'] }}"
                                                               placeholder="{{ number_format($item['price'] * 0.9, 2) }}"
                                                               data-original-price="{{ $item['price'] }}"
                                                               data-quantity="{{ $item['quantity'] }}">
                                                    </div>
                                                    <small class="text-muted">Max: ₱{{ number_format($item['price'], 2) }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Savings:</label>
                                                    <div class="text-success fw-bold" id="savings_{{ $item['id'] }}">₱0.00</div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                    <div class="mb-3">
                                        <label for="negotiation_notes" class="form-label">
                                            <i class="fas fa-comment me-2"></i>Message to Farmer (Optional)
                                        </label>
                                        <textarea class="form-control"
                                                  id="negotiation_notes"
                                                  name="negotiation_notes"
                                                  rows="3"
                                                  placeholder="Add a message explaining your price offer...">{{ old('negotiation_notes') }}</textarea>
                                    </div>

                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Note:</strong> Stock will not be reserved until the farmer accepts your offer. The order will be created after acceptance.
                                    </div>
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
                            <input type="hidden" name="payment_method" value="cash">
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
                        @if($farmerCount > 1)
                            <span class="badge bg-info ms-2">{{ $farmerCount }} Vendors</span>
                        @endif
                    </h5>

                    @if($farmerCount > 1)
                        <!-- Multi-vendor breakdown -->
                        @foreach($groupedItems as $farmerId => $farmerItems)
                            @php
                                $firstItem = $farmerItems->first();
                                $farmerSubtotal = $farmerItems->sum('subtotal');
                            @endphp
                            <div class="vendor-summary mb-3 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-bold text-success">{{ $firstItem['farmer_name'] ?? 'Farmer' }}</small>
                                    <small class="text-muted">{{ $farmerItems->count() }} item(s)</small>
                                </div>
                                @foreach($farmerItems as $item)
                                    <div class="d-flex justify-content-between mb-1">
                                        <div>
                                            <small>{{ $item['name'] }}</small>
                                            <br><small class="text-muted">{{ $item['quantity'] }} {{ $item['unit'] }}</small>
                                        </div>
                                        <span>₱{{ number_format($item['subtotal'], 2) }}</span>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                    <small class="fw-bold">Subtotal:</small>
                                    <small class="fw-bold text-success">₱{{ number_format($farmerSubtotal, 2) }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Single vendor items -->
                        @foreach($items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <small>{{ $item['name'] }}</small>
                                    <br><small class="text-muted">{{ $item['quantity'] }} {{ $item['unit'] }}</small>
                                </div>
                                <span>₱{{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                        @endforeach
                    @endif

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

                    @if($farmerCount > 1)
                        <!-- Multi-vendor info -->
                        <div class="alert alert-info mt-3">
                            <h6><i class="fas fa-info-circle me-2"></i> Multi-Vendor Order</h6>
                            <small>
                                Your order contains items from {{ $farmerCount }} different farmers.
                                @if($farmerCount > 1)
                                    You'll receive pickup details for each farmer after placing your order.
                                @endif
                            </small>
                        </div>
                    @endif

                    <!-- Farmer Contact Info -->
                    <div class="farmer-contact-info mt-3">
                        <h6><i class="fas fa-phone"></i> Need Help?</h6>
                        <p class="mb-1">Contact the farmers directly for questions about your order.</p>
                        <p class="mb-1">
                            <i class="fas fa-phone-alt me-2"></i>
                            <strong>Phone:</strong>
                            <a href="tel:09075552372" class="text-decoration-none">09075552372</a>
                        </p>
                        <small class="text-muted">Supporting local agriculture since 2024</small>
                    </div>

                    <!-- Place Order Button -->
                    <div class="mt-4">
                        <button type="submit" form="checkoutForm" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check me-2"></i> Place Order
                        </button>
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
    const deliveryFeeDisplay = document.getElementById('delivery-fee-display');
    const deliveryInfo = document.getElementById('delivery-info');
    const subtotal = {{ $total }};
    const shippingCost = {{ $shippingCost ?? 100 }};

    // Address input fields for delivery fee calculation
    const addressInputs = ['address', 'city', 'state'];
    let deliveryFeeCalculationTimeout;

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
                // Don't set shipping cost immediately - wait for calculation
                deliveryFeeDisplay.textContent = '(Calculating...)';
                deliveryInfo.textContent = 'Enter your address to calculate delivery fee';
                updateTotal();
            } else {
                addressSection.style.display = 'none';
                shippingInput.value = 0;
                shippingDisplay.textContent = 'Free';
                deliveryFeeDisplay.textContent = '(Free)';
                deliveryInfo.textContent = '';
                updateTotal();
            }
        });
    });

    // Add event listeners to address inputs for automatic delivery fee calculation
    addressInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function() {
                // Clear previous timeout
                if (deliveryFeeCalculationTimeout) {
                    clearTimeout(deliveryFeeCalculationTimeout);
                }

                // Set new timeout to calculate delivery fee after user stops typing
                deliveryFeeCalculationTimeout = setTimeout(() => {
                    calculateDeliveryFee();
                }, 1000); // Wait 1 second after user stops typing
            });
        }
    });

    function calculateDeliveryFee() {
        const address = document.getElementById('address').value.trim();
        const city = document.getElementById('city').value.trim();
        const state = document.getElementById('state').value.trim();

        // Check if delivery option is selected and all required fields are filled
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked');
        if (!deliveryOption || deliveryOption.value !== 'delivery') {
            return;
        }

        if (!address || !city || !state) {
            deliveryFeeDisplay.textContent = '(Enter address details)';
            deliveryInfo.textContent = 'Please fill in address, city, and province';
            return;
        }

        // Show loading state
        deliveryFeeDisplay.textContent = '(Calculating...)';
        deliveryInfo.textContent = 'Calculating delivery fee...';

        // Get farmer location from the first item (if available)
        const farmerLocation = '{{ !empty($items) ? ($items[0]["farmer_location"] ?? "Metro Manila, Philippines") : "Metro Manila, Philippines" }}';

        // Make AJAX request to calculate delivery fee
        fetch('{{ route("checkout.calculate-delivery-fee") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                address: address,
                city: city,
                province: state,
                farmer_location: farmerLocation
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const deliveryFee = data.delivery_fee;
                shippingInput.value = deliveryFee;
                shippingDisplay.textContent = '₱' + deliveryFee.toFixed(2);
                deliveryFeeDisplay.textContent = '(₱' + deliveryFee.toFixed(2) + ')';
                deliveryInfo.textContent = `${data.distance} • ${data.estimated_delivery_time}`;
                updateTotal();
            } else {
                deliveryFeeDisplay.textContent = '(Error calculating)';
                deliveryInfo.textContent = 'Unable to calculate delivery fee. Using default rate.';
                shippingInput.value = shippingCost;
                shippingDisplay.textContent = '₱' + shippingCost.toFixed(2);
                updateTotal();
            }
        })
        .catch(error => {
            console.error('Error calculating delivery fee:', error);
            deliveryFeeDisplay.textContent = '(Error calculating)';
            deliveryInfo.textContent = 'Unable to calculate delivery fee. Using default rate.';
            shippingInput.value = shippingCost;
            shippingDisplay.textContent = '₱' + shippingCost.toFixed(2);
            updateTotal();
        });
    }

    function updateTotal() {
        const shipping = parseFloat(shippingInput.value) || 0;
        let calculatedSubtotal = subtotal;

        // If negotiation is enabled, calculate from negotiated prices
        const negotiationEnabled = document.getElementById('enable_negotiation')?.checked;
        if (negotiationEnabled) {
            calculatedSubtotal = 0;
            document.querySelectorAll('.negotiated-price-input').forEach(input => {
                const negotiatedPrice = parseFloat(input.value) || 0;
                const quantity = parseFloat(input.dataset.quantity) || 1;
                if (negotiatedPrice > 0) {
                    calculatedSubtotal += negotiatedPrice * quantity;
                } else {
                    // Use original price if no negotiation entered
                    const originalPrice = parseFloat(input.dataset.originalPrice) || 0;
                    calculatedSubtotal += originalPrice * quantity;
                }
            });
        }

        const total = calculatedSubtotal + shipping;
        totalDisplay.textContent = '₱' + total.toFixed(2);
    }

    // Price Negotiation Functionality
    const enableNegotiation = document.getElementById('enable_negotiation');
    const negotiationSection = document.getElementById('negotiation_section');

    if (enableNegotiation && negotiationSection) {
        // Toggle negotiation section
        enableNegotiation.addEventListener('change', function() {
            if (this.checked) {
                negotiationSection.style.display = 'block';
            } else {
                negotiationSection.style.display = 'none';
                // Clear all negotiated prices
                document.querySelectorAll('.negotiated-price-input').forEach(input => {
                    input.value = '';
                    const itemId = input.id.replace('negotiated_price_', '');
                    document.getElementById('savings_' + itemId).textContent = '₱0.00';
                });
            }
            updateTotal();
        });

        // Calculate savings for each item
        document.querySelectorAll('.negotiated-price-input').forEach(input => {
            input.addEventListener('input', function() {
                const originalPrice = parseFloat(this.dataset.originalPrice) || 0;
                const quantity = parseFloat(this.dataset.quantity) || 1;
                const negotiatedPrice = parseFloat(this.value) || 0;

                // Validate max price
                if (negotiatedPrice > originalPrice) {
                    this.value = originalPrice.toFixed(2);
                    alert('Negotiated price cannot be higher than original price.');
                    return;
                }

                // Calculate savings
                const originalTotal = originalPrice * quantity;
                const negotiatedTotal = negotiatedPrice > 0 ? negotiatedPrice * quantity : originalTotal;
                const savings = originalTotal - negotiatedTotal;

                const itemId = this.id.replace('negotiated_price_', '');
                const savingsElement = document.getElementById('savings_' + itemId);
                if (savingsElement) {
                    savingsElement.textContent = '₱' + savings.toFixed(2);
                    savingsElement.className = savings > 0 ? 'text-success fw-bold' : 'text-muted';
                }

                updateTotal();
            });
        });
    }

    // Update delivery option input when radio changes
    deliveryOptions.forEach(option => {
        option.addEventListener('change', function() {
            document.getElementById('delivery_option_input').value = this.value;
        });
    });

    // Form validation and submission
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;

        // Ensure delivery option is set in hidden input
        document.getElementById('delivery_option_input').value = deliveryOption;

        if (deliveryOption === 'delivery') {
            const address = document.getElementById('address').value.trim();
            const city = document.getElementById('city').value.trim();
            const state = document.getElementById('state').value.trim();
            const zip = document.getElementById('zip').value.trim();

            if (!address || !city || !state || !zip) {
                e.preventDefault();
                alert('Please fill in all delivery address fields (Address, City, Province, and ZIP Code).');
                return false;
            }
        }

        // Ensure shipping is set
        const shipping = parseFloat(shippingInput.value) || 0;
        document.getElementById('shipping_input').value = shipping;

        // Validate negotiation prices if negotiation is enabled
        const negotiationEnabled = document.getElementById('enable_negotiation')?.checked;
        if (negotiationEnabled) {
            let hasInvalidPrice = false;
            document.querySelectorAll('.negotiated-price-input').forEach(input => {
                const negotiatedPrice = parseFloat(input.value) || 0;
                const originalPrice = parseFloat(input.dataset.originalPrice) || 0;

                if (negotiatedPrice > originalPrice) {
                    hasInvalidPrice = true;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (hasInvalidPrice) {
                e.preventDefault();
                alert('Please ensure all negotiated prices are not higher than original prices.');
                return false;
            }
        }

        // Show loading state
        const submitButton = document.querySelector('button[type="submit"][form="checkoutForm"]');
        if (submitButton) {
            submitButton.disabled = true;
            const buttonText = negotiationEnabled ? 'Submitting Negotiation...' : 'Processing...';
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + buttonText;
        }
    });
});
</script>
@endsection