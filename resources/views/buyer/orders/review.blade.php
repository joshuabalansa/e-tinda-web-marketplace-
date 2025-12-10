@extends('layouts.shop')

@section('content')
<!-- Review Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 text-success fw-bold">Leave a Review</h1>
                <p class="lead mb-0">Order #{{ $order->id }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-outline-success">
                    <i class="fas fa-arrow-left me-2"></i> Back to Order
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i> Rate Your Products
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Please rate the products you received. Your feedback helps other buyers and helps farmers improve their products.
                    </p>

                    @if($productsToReview->count() === 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> All products in this order have already been reviewed.
                        </div>
                        <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-success">
                            <i class="fas fa-arrow-left me-2"></i> Back to Order
                        </a>
                    @else
                        <form action="{{ route('buyer.orders.review.submit', $order->id) }}" method="POST" id="reviewForm">
                            @csrf

                            @foreach($productsToReview as $item)
                                @php
                                    $product = $item->product;
                                @endphp

                                <div class="product-review-item mb-4 p-4 border rounded">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-2">
                                            @if($product)
                                                <img src="{{ $product->getImageUrl() }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-fluid rounded"
                                                     style="max-width: 100px; height: auto;">
                                            @else
                                                <div class="bg-light text-center py-3 rounded" style="width: 100px; height: 75px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-10">
                                            <h5 class="fw-bold mb-1">{{ $product->name ?? 'Product Unavailable' }}</h5>
                                            <p class="text-muted small mb-0">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                    </div>

                                    <input type="hidden" name="reviews[{{ $loop->index }}][product_id]" value="{{ $product->id ?? '' }}">

                                    <!-- Star Rating -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                                        <div class="star-rating">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio"
                                                       name="reviews[{{ $loop->index }}][rating]"
                                                       id="rating_{{ $loop->index }}_{{ $i }}"
                                                       value="{{ $i }}"
                                                       required
                                                       class="star-rating-input">
                                                <label for="rating_{{ $loop->index }}_{{ $i }}"
                                                       class="star-rating-label"
                                                       title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                                    <i class="fas fa-star"></i>
                                                </label>
                                            @endfor
                                        </div>
                                        <small class="text-muted">Click on a star to rate (1 = Poor, 5 = Excellent)</small>
                                    </div>

                                    <!-- Comment -->
                                    <div class="mb-3">
                                        <label for="comment_{{ $loop->index }}" class="form-label fw-bold">Comment (Optional)</label>
                                        <textarea
                                            name="reviews[{{ $loop->index }}][comment]"
                                            id="comment_{{ $loop->index }}"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Share your experience with this product..."
                                            maxlength="1000"></textarea>
                                        <small class="text-muted">Maximum 1000 characters</small>
                                    </div>

                                    @if(!$loop->last)
                                        <hr>
                                    @endif
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Review
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.star-rating-input {
    display: none;
}

.star-rating-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.star-rating-label:hover,
.star-rating-label:hover ~ .star-rating-label {
    color: #ffc107;
}

.star-rating-input:checked ~ .star-rating-label,
.star-rating-input:checked ~ .star-rating-label ~ .star-rating-label {
    color: #ffc107;
}

.product-review-item {
    background-color: #f8f9fa;
}

.product-review-item:hover {
    background-color: #e9ecef;
    transition: background-color 0.2s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure at least one rating is selected before form submission
    const form = document.getElementById('reviewForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const ratingInputs = form.querySelectorAll('input[name*="[rating]"]');
            let hasRating = false;

            ratingInputs.forEach(input => {
                if (input.checked) {
                    hasRating = true;
                }
            });

            if (!hasRating) {
                e.preventDefault();
                alert('Please rate at least one product before submitting.');
                return false;
            }
        });
    }
});
</script>
@endsection

