@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Section -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold text-dark mb-2">{{ __('dashboard.product_management') }}</h1>
                    <p class="text-muted small mb-0">{{ __('dashboard.manage_products') }}</p>
                </div>
                <a href="{{ route('farmer.products.create') }}" class="btn btn-primary btn-md btn-lg-md shadow-sm">
                    <i class="fas fa-plus me-2"></i> {{ __('dashboard.add_new_product') }}
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="card shadow-sm border-0 mb-3 mb-md-4">
        <div class="card-body p-3 p-md-4">
            <div class="row g-2 g-md-3">
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search products...">
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Fruits">Fruits</option>
                        <option value="Grains">Grains</option>
                        <option value="Dairy">Dairy</option>
                        <option value="Meat">Meat</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="available">Available</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-flex">
                    <button class="btn btn-outline-secondary w-100" id="clearFilters">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-3 g-md-4" id="productsGrid">
        @forelse ($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 product-card"
                 data-name="{{ strtolower($product->name) }}"
                 data-category="{{ $product->category }}"
                 data-status="{{ $product->status }}">
                <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                    <!-- Product Image -->
                    <div class="position-relative">
                        @if($product->image_url)
                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                 alt="{{ $product->name }}"
                                 class="card-img-top product-image"
                                 style="height: 180px; object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal"
                                 data-bs-target="#imagePreviewModal"
                                 data-image="{{ asset('storage/' . $product->image_url) }}"
                                 data-name="{{ $product->name }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-image text-muted" style="font-size: 2.5rem;"></i>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge rounded-pill fs-7 px-2 py-1 {{ $product->status === 'available' ? 'bg-success' : ($product->status === 'out_of_stock' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                            </span>
                        </div>

                        <!-- Category Badge -->
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary rounded-pill fs-7 px-2 py-1">{{ $product->category }}</span>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="card-body d-flex flex-column p-3 p-md-4">
                        <h6 class="card-title fw-bold text-dark mb-2 fs-6">{{ $product->name }}</h6>

                        <!-- Price and Stock Info -->
                        <div class="row text-center mb-2 g-1">
                            <div class="col-6">
                                <div class="border-end border-1">
                                    <h6 class="text-primary mb-0 fw-bold fs-6">₱{{ number_format($product->price_per_unit, 2) }}</h6>
                                    <small class="text-muted d-block small">per {{ $product->unit_type }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success mb-0 fw-bold fs-6">{{ $product->stock_quantity }}</h6>
                                <small class="text-muted d-block small">{{ $product->unit_type }} in stock</small>
                            </div>
                        </div>

                        <!-- Harvest Date -->
                        <div class="mb-3">
                            <small class="text-muted small">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Harvested: {{ \Carbon\Carbon::parse($product->harvest_date)->format('M d, Y') }}
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="d-grid gap-1">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('farmer.products.show', $product) }}"
                                       class="btn btn-outline-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('farmer.products.edit', $product) }}"
                                       class="btn btn-outline-warning btn-sm" title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('farmer.products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                                title="Delete Product"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4 py-md-5">
                        <i class="fas fa-box-open text-muted mb-3 mb-md-4" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mb-2 mb-md-3">No products found</h5>
                        <p class="text-muted mb-3 mb-md-4 small">Start by adding your first product to showcase your farm's produce.</p>
                        <a href="{{ route('farmer.products.create') }}" class="btn btn-primary btn-md btn-lg-md">
                            <i class="fas fa-plus me-2"></i> Add Your First Product
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4 mt-md-5">
            <nav aria-label="Products pagination">
                {{ $products->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    @endif
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="imagePreviewModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" class="img-fluid w-100" alt="Product Image" style="max-height: 70vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow-lg {
        transition: all 0.3s ease;
    }
    .hover-shadow-lg:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15) !important;
    }
    .product-image {
        transition: transform 0.3s ease;
    }
    .product-image:hover {
        transform: scale(1.03);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .form-select, .form-control {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
    }
    .btn {
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
    }
    .btn-group .btn {
        border-radius: 0.375rem !important;
        padding: 0.25rem 0.5rem;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem !important;
        border-bottom-right-radius: 0.375rem !important;
    }
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
        font-size: 0.875rem;
    }
    .input-group .form-control {
        border-radius: 0 0.5rem 0.5rem 0;
    }

    /* Mobile responsive utilities */
    .btn-md { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
    .btn-lg-md { padding: 0.5rem 1rem; font-size: 1rem; }
    .h-md-3 { font-size: 1.75rem; }
    .fs-7 { font-size: 0.75rem; }

    @media (max-width: 768px) {
        .btn-md { padding: 0.25rem 0.5rem; font-size: 0.8rem; }
        .btn-lg-md { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
        .h-md-3 { font-size: 1.5rem; }
        .card-body { padding: 0.75rem; }
        .product-image { height: 140px !important; }
        .card-img-top.bg-light { height: 140px !important; }
    }

    @media (max-width: 576px) {
        .btn-md { padding: 0.2rem 0.4rem; font-size: 0.75rem; }
        .btn-lg-md { padding: 0.25rem 0.5rem; font-size: 0.8rem; }
        .h-md-3 { font-size: 1.25rem; }
        .card-body { padding: 0.5rem; }
        .product-image { height: 120px !important; }
        .card-img-top.bg-light { height: 120px !important; }
    }

    /* Full-width layout improvements */
    @media (min-width: 1200px) {
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
    }

    @media (min-width: 1400px) {
        .container-fluid {
            max-width: 1600px;
        }
    }

    @media (min-width: 1600px) {
        .container-fluid {
            max-width: 1800px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview functionality
    $('.product-image').on('click', function() {
        var imageUrl = $(this).data('image');
        var productName = $(this).data('name');

        $('#imagePreviewModalLabel').text(productName);
        $('#imagePreviewModal img').attr('src', imageUrl);
    });

    // Search and filter functionality with debouncing
    let searchTimeout;

    function filterProducts() {
        var searchValue = $('#searchInput').val().toLowerCase().trim();
        var categoryValue = $('#categoryFilter').val();
        var statusValue = $('#statusFilter').val();

        var visibleCount = 0;
        var totalCount = $('.product-card').length;

        $('.product-card').each(function() {
            var productName = $(this).data('name');
            var productCategory = $(this).data('category');
            var productStatus = $(this).data('status');

            var matchesSearch = !searchValue || productName.includes(searchValue);
            var matchesCategory = !categoryValue || productCategory === categoryValue;
            var matchesStatus = !statusValue || productStatus === statusValue;

            if (matchesSearch && matchesCategory && matchesStatus) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        // Show/hide no results message
        if (visibleCount === 0 && (searchValue || categoryValue || statusValue)) {
            if ($('#noResultsMessage').length === 0) {
                $('#productsGrid').append(`
                    <div class="col-12" id="noResultsMessage">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-search text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mb-2">No products found</h5>
                                <p class="text-muted mb-3 small">Try adjusting your search criteria or filters.</p>
                                <button class="btn btn-outline-primary btn-sm" id="resetSearch">
                                    <i class="fas fa-times me-1"></i> Reset Search
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        } else {
            $('#noResultsMessage').remove();
        }
    }

    // Bind filter events with debouncing for search
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterProducts, 300);
    });

    $('#categoryFilter, #statusFilter').on('change', filterProducts);

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('#statusFilter').val('');
        $('.product-card').show();
        $('#noResultsMessage').remove();
    });

    // Reset search button
    $(document).on('click', '#resetSearch', function() {
        $('#clearFilters').click();
    });

    // Initial filter
    filterProducts();
});
</script>
@endpush
@endsection
