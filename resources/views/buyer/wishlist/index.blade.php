@extends('layouts.shop')

@section('content')
<!-- Wishlist Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="display-4 text-success fw-bold">My Wishlist</h1>
        <p class="lead">Save products for later purchase</p>
    </div>
</div>

<!-- Wishlist Content Section -->
<div class="container my-5">
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

    <div class="row">
        <div class="col-md-12">
            @if($wishlistItems->count() > 0)
                <div class="row">
                    @foreach($wishlistItems as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-success">
                            <div class="card-img-top-container" style="height: 200px; overflow: hidden;">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                         alt="{{ $item->product->name }}"
                                         class="card-img-top h-100 w-100"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-success">{{ $item->product->name ?? 'Product Unavailable' }}</h5>

                                @if($item->product)
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-user text-success"></i>
                                        {{ $item->product->user->name ?? 'Unknown Seller' }}
                                    </p>

                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-tag text-success"></i>
                                        {{ $item->product->category }}
                                    </p>

                                    <div class="mb-3">
                                        <span class="h4 text-success fw-bold">₱{{ number_format($item->product->price_per_unit, 2) }}</span>
                                        <span class="text-muted">/ {{ $item->product->unit_type }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <span class="badge bg-{{ $item->product->status === 'available' ? 'success' : 'warning' }} rounded-pill">
                                            {{ ucfirst($item->product->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-auto">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <a href="{{ route('shop.product', $item->product->id) }}" class="btn btn-success w-100">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-outline-danger w-100 remove-wishlist"
                                                        data-id="{{ $item->id }}"
                                                        data-product="{{ $item->product->name }}">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-auto">
                                        <p class="text-muted text-center">This product is no longer available</p>
                                        <button class="btn btn-outline-danger w-100 remove-wishlist"
                                                data-id="{{ $item->id }}">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $wishlistItems->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-heart text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">Your Wishlist is Empty</h3>
                    <p class="text-muted mb-4">Start adding products to your wishlist for easy access later.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-shopping-bag"></i> Browse Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Remove Wishlist Item Modal -->
<div class="modal fade" id="removeWishlistModal" tabindex="-1" aria-labelledby="removeWishlistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeWishlistModalLabel">Remove from Wishlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove <strong id="productName"></strong> from your wishlist?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemove">Remove</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.remove-wishlist').click(function() {
        var id = $(this).data('id');
        var productName = $(this).data('product') || 'this item';

        $('#productName').text(productName);
        $('#confirmRemove').data('id', id);
        $('#removeWishlistModal').modal('show');
    });

    $('#confirmRemove').click(function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/buyer/wishlist/remove/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while removing the item.');
            }
        });

        $('#removeWishlistModal').modal('hide');
    });
});
</script>
@endpush
