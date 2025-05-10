@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name ?? 'Product Details' }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="card border-0">
                <div class="main-product-image mb-3">
                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1518977676601-b53f82aba655' }}"
                         class="img-fluid rounded"
                         alt="Product Image"
                         style="width: 100%; height: 400px; object-fit: cover;">
                </div>
                <div class="d-flex gap-2">
                    <div class="thumbnail-image">
                        <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1518977676601-b53f82aba655' }}"
                             class="img-fluid rounded cursor-pointer"
                             style="width: 80px; height: 80px; object-fit: cover;"
                             alt="Thumbnail 1">
                    </div>
                    <!-- Add more thumbnail images here -->
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="product-details">
                <!-- Title and Badge -->
                <div class="d-flex align-items-center mb-3">
                    <h1 class="h2 mb-0">{{ $product->name ?? 'Fresh Tomatoes' }}</h1>
                    <span class="badge bg-success ms-3">Organic</span>
                </div>

                <!-- Rating -->
                <div class="rating mb-3">
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star-half-alt text-warning"></i>
                    <span class="ms-2 text-muted">(42 reviews)</span>
                </div>

                <!-- Price -->
                <div class="pricing mb-4">
                    <h2 class="text-success mb-0">₱{{ $product->price ?? '3.99' }}/lb</h2>
                    <small class="text-muted">Inclusive of all taxes</small>
                </div>

                <!-- Vendor Info -->
                <div class="vendor-info card bg-light border-0 mb-4 p-3">
                    <h6 class="mb-2">Vendor Information</h6>
                    <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Vendor Logo">
                        <div>
                            <h6 class="mb-0">{{ $product->vendor ?? 'Green Harvest Farms' }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $product->location ?? 'Local Farm, Philippines' }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Quantity Selector -->
                <div class="quantity-selector mb-4">
                    <label class="form-label">Quantity</label>
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                        <input type="number" class="form-control text-center" value="1" min="1" id="quantity">
                        <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <button class="btn btn-success btn-lg mb-4 w-100">
                    <i class="fas fa-cart-plus me-2"></i>
                    Add to Cart
                </button>

                <!-- Product Description -->
                <div class="product-description mb-4">
                    <h5>Product Description</h5>
                    <p class="text-muted">
                        {{ $product->description ?? 'Juicy, vine-ripened tomatoes grown locally without pesticides. Our tomatoes are carefully harvested at peak ripeness to ensure the best flavor and nutritional value. Perfect for salads, cooking, or eating fresh.' }}
                    </p>
                </div>

                <!-- Additional Info -->
                <div class="additional-info">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <i class="fas fa-truck text-success mb-2"></i>
                                    <h6 class="card-title">Free Delivery</h6>
                                    <p class="card-text small">On orders above ₱1000</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <i class="fas fa-undo text-success mb-2"></i>
                                    <h6 class="card-title">Easy Returns</h6>
                                    <p class="card-text small">7 day return policy</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <div class="related-products mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <!-- Related Product Cards will go here -->
        </div>
    </div>
</div>

<script>
    // Quantity selector functionality
    document.getElementById('decrease-qty').addEventListener('click', function() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    });

    document.getElementById('increase-qty').addEventListener('click', function() {
        const input = document.getElementById('quantity');
        input.value = parseInt(input.value) + 1;
    });

    // Add to cart functionality
    document.querySelector('.btn-success').addEventListener('click', function() {
        const toast = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto">Added to Cart</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Product has been added to your cart.
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(t => t.classList.remove('show'));
            setTimeout(() => toasts.forEach(t => t.parentElement.remove()), 300);
        }, 3000);
    });
</script>
@endsection