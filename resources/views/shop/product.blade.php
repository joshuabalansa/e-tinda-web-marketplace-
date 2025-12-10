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
                        class="btn btn-sm wishlist-btn {{ $isInWishlist ? 'btn-danger' : 'btn-outline-danger' }}"
                        data-product-id="{{ $productData['id'] }}"
                        data-in-wishlist="{{ $isInWishlist ? 'true' : 'false' }}"
                        onclick="toggleWishlist(this)"
                        title="{{ $isInWishlist ? 'Remove from favorites' : 'Add to favorites' }}"
                    >
                        <i class="fas fa-heart"></i>
                    </button>
                @endauth
            </div>

            <!-- Price -->
            <h2 class="text-success mb-2">₱{{ number_format($productData['price'], 2) }}/{{ $productData['unit'] }}</h2>

            <!-- Rating Summary -->
            @if($productData['total_reviews'] > 0)
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($productData['average_rating']))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $productData['average_rating'])
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="fw-bold me-2">{{ $productData['average_rating'] }}</span>
                        <span class="text-muted">({{ $productData['total_reviews'] }} review{{ $productData['total_reviews'] !== 1 ? 's' : '' }})</span>
                    </div>
                </div>
            @endif

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
            <div class="card mb-4">
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

            <!-- Reviews Button -->
            <div class="mb-4">
                <button type="button" class="btn btn-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#reviewsModal" onclick="loadReviews({{ $productData['id'] }})">
                    <i class="fas fa-star me-2"></i>View Reviews
                    @if($productData['total_reviews'] > 0)
                        <span class="badge bg-light text-success ms-2">{{ $productData['total_reviews'] }}</span>
                    @endif
                </button>
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
                <a href="{{ route('shop.product.show', $relatedProduct['id']) }}" class="product-link">
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

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="reviewsModalLabel">
                    <i class="fas fa-star me-2"></i>Product Reviews
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading reviews...</p>
                </div>
            </div>
        </div>
    </div>
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
                // Change button styling to outline
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-danger');
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
                // Change button styling to filled
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-danger');
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

// Smooth transition for wishlist button
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.querySelector('.wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.style.transition = 'all 0.3s ease';
    }
});

// Load reviews function
function loadReviews(productId) {
    const reviewsContent = document.getElementById('reviewsContent');
    reviewsContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading reviews...</p>
        </div>
    `;

    fetch(`/shop/product/${productId}/reviews`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayReviews(data);
            } else {
                reviewsContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>Failed to load reviews.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            reviewsContent.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>Error loading reviews. Please try again.
                </div>
            `;
        });
}

// Display reviews in modal
function displayReviews(data) {
    const reviewsContent = document.getElementById('reviewsContent');

    if (data.total_reviews === 0) {
        reviewsContent.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Reviews Yet</h5>
                <p class="text-muted">Be the first to review this product!</p>
            </div>
        `;
        return;
    }

    let html = `
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">
                        <span class="text-warning">
                            ${generateStars(data.average_rating)}
                        </span>
                        <span class="ms-2">${data.average_rating}</span>
                    </h4>
                    <p class="text-muted mb-0 small">Based on ${data.total_reviews} review${data.total_reviews !== 1 ? 's' : ''}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="reviews-list">
    `;

    data.reviews.forEach(review => {
        html += `
            <div class="review-item mb-4 pb-4 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-1 fw-bold">${escapeHtml(review.buyer_name)}</h6>
                        <div class="text-warning mb-2">
                            ${generateStars(review.rating)}
                        </div>
                    </div>
                    <small class="text-muted">${review.review_date}</small>
                </div>
                ${review.comment ? `<p class="mb-0">${escapeHtml(review.comment)}</p>` : '<p class="text-muted mb-0"><em>No comment provided</em></p>'}
            </div>
        `;
    });

    html += `</div>`;
    reviewsContent.innerHTML = html;
}

// Generate star rating HTML
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    let stars = '';
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    return stars;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<style>
.wishlist-btn {
    transition: all 0.3s ease !important;
    border-radius: 50% !important;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.wishlist-btn:hover {
    transform: scale(1.1);
}

.wishlist-btn i {
    font-size: 16px;
}
</style>
@endsection