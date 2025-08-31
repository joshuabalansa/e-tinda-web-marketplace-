@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card border-0">
                <img src="{{ $productData['image'] }}" class="img-fluid rounded" alt="{{ $productData['name'] }}">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h1 class="mb-0">{{ $productData['name'] }}</h1>
                @auth
                    <button 
                        type="button" 
                        class="btn btn-outline-danger btn-sm wishlist-btn" 
                        data-product-id="{{ $productData['id'] }}"
                        data-in-wishlist="{{ $isInWishlist ? 'true' : 'false' }}"
                        onclick="toggleWishlist(this)"
                        title="{{ $isInWishlist ? 'Remove from favorites' : 'Add to favorites' }}"
                    >
                        <i class="fas fa-heart {{ $isInWishlist ? 'text-danger' : '' }}"></i>
                    </button>
                @endauth
            </div>

            <!-- Price -->
            <h2 class="text-success mb-4">₱{{ number_format($productData['price'], 2) }}/{{ $productData['unit'] }}</h2>

            <!-- Description -->
            <p class="mb-4">{{ $productData['description'] }}</p>

            <!-- Vendor Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ __('shop.vendor_information') }}</h5>
                    <p class="mb-1"><strong>{{ __('shop.farm') }}:</strong> {{ $productData['vendor'] }}</p>
                    <p class="mb-1"><strong>{{ __('shop.location') }}:</strong> {{ $productData['location'] }}</p>
                </div>
            </div>

            <!-- Add to Cart -->
            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $productData['id'] }}">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="input-group" style="width: 130px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                            <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $productData['stock'] }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                        </div>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-success btn-lg" {{ $productData['stock'] <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus me-2"></i>{{ $productData['stock'] <= 0 ? __('shop.out_of_stock') : __('shop.add_to_cart') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Additional Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('shop.product_details') }}</h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>{{ __('shop.category') }}:</strong> {{ $productData['category'] }}</li>
                        <li><strong>{{ __('shop.stock') }}:</strong> {{ $productData['stock'] }} {{ $productData['unit'] }}s {{ __('shop.available') }}</li>
                        <li><strong>{{ __('shop.harvest_date') }}:</strong> {{ $productData['harvest_date'] }}</li>
                        <li><strong>{{ __('shop.storage') }}:</strong> {{ $productData['storage'] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">{{ __('shop.related_products') }}</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col">
                <a href="{{ route('shop.product', ['id' => $relatedProduct['id']]) }}" class="product-link">
                    <div class="card h-100 product-card">
                        <img src="{{ $relatedProduct['image'] }}" class="card-img-top" alt="{{ $relatedProduct['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct['name'] }}</h5>
                            <p class="card-text text-success mb-0">₱{{ number_format($relatedProduct['price'], 2) }}/{{ $relatedProduct['unit'] }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function toggleWishlist(button) {
    const productId = button.getAttribute('data-product-id');
    const isInWishlist = button.getAttribute('data-in-wishlist') === 'true';
    const icon = button.querySelector('i');
    const title = button.getAttribute('title');
    
    if (isInWishlist) {
        // Remove from wishlist
        fetch(`/buyer/wishlist/remove-product/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.setAttribute('data-in-wishlist', 'false');
                button.setAttribute('title', 'Add to favorites');
                icon.classList.remove('text-danger');
                showToast('Product removed from favorites', 'success');
            } else {
                showToast(data.message || 'Error removing from favorites', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing from favorites', 'error');
        });
    } else {
        // Add to wishlist
        fetch('/buyer/wishlist/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.setAttribute('data-in-wishlist', 'true');
                button.setAttribute('title', 'Remove from favorites');
                icon.classList.add('text-danger');
                showToast('Product added to favorites', 'success');
            } else {
                showToast(data.message || 'Error adding to favorites', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error adding to favorites', 'error');
        });
    }
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050;';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}
</script>
@endsection