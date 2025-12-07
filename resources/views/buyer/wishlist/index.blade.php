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
                                @if($item->product)
                                    <img src="{{ $item->product->getImageUrl() }}"
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
                                        <a href="{{ route('shop.product.show', $item->product->id) }}" class="btn btn-success w-100">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-auto">
                                        <p class="text-muted text-center">This product is no longer available</p>
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


@endsection
