@extends('layouts.farmer')

@section('content')
<!-- Add New Product Button - Left Side -->
<div class="row mb-3">
    <div class="col-sm-12">
        <div class="text-left">
            <a href="{{ route('farmer.products.create') }}" class="btn btn-primary btn-sm">
                <i class="entypo-plus"></i> Add New Product
            </a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-newspaper"></i> Product Management</h4>
                </div>
            </div>
            <div class="panel-body">
                <!-- Status Counter Cards -->
                <div class="row mb-4" id="statusCounters">
                    <div class="col-sm-4">
                        <div class="tile-progress tile-green status-counter-card" data-status="available">
                            <div class="tile-header">
                                <h3>Available Products</h3>
                                <span>Ready for sale</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4 class="status-count">0</h4>
                                <span>products</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="tile-progress tile-orange status-counter-card" data-status="out_of_stock">
                            <div class="tile-header">
                                <h3>Out of Stock</h3>
                                <span>Need restocking</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4 class="status-count">0</h4>
                                <span>products</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="tile-progress tile-red status-counter-card" data-status="unavailable">
                            <div class="tile-header">
                                <h3>Unavailable</h3>
                                <span>Not for sale</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4 class="status-count">0</h4>
                                <span>products</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="row mb-3">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="searchInput" class="control-label">Search Products</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="entypo-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-8">
                        <div class="form-group">
                            <label for="categoryFilter" class="control-label">Category</label>
                            <select id="categoryFilter" class="form-control">
                                <option value="">All Categories</option>
                                <option value="Vegetables">🥬 Vegetables</option>
                                <option value="Fruits">🍎 Fruits</option>
                                <option value="Grains">🌾 Grains</option>
                                <option value="Dairy">🥛 Dairy</option>
                                <option value="Meat">🥩 Meat</option>
                                <option value="Other">📦 Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button class="btn btn-default btn-block" id="clearFilters" style="height: 34px; line-height: 1.42857143; text-align: center;">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="results-meta small text-muted">
                            <i class="entypo-info"></i> <span id="resultCount">{{ $products->total() }} results</span>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row" id="productsGrid">
                    @forelse ($products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 product-card"
                             data-name="{{ strtolower($product->name) }}"
                             data-category="{{ $product->category }}"
                             data-status="{{ $product->status }}">
                            <div class="panel panel-default card-elevated">
                                <!-- Product Image -->
                                <div class="panel-body p-0">
                                    <div class="position-relative">
                                        @if($product->image_url)
                                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-responsive product-image"
                                                 data-toggle="modal"
                                                 data-target="#imagePreviewModal"
                                                 data-image="{{ asset('storage/' . $product->image_url) }}"
                                                 data-name="{{ $product->name }}">
                                        @else
                                            <div class="no-image-placeholder">
                                                <i class="entypo-image"></i>
                                            </div>
                                        @endif

                                        <!-- Status Badge -->
                                        <div class="status-badge">
                                            <span class="badge {{ $product->status === 'available' ? 'badge-success' : ($product->status === 'out_of_stock' ? 'badge-warning' : 'badge-danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                            </span>
                                        </div>

                                        <!-- Category Badge -->
                                        <div class="category-badge">
                                            <span class="badge badge-primary">{{ $product->category }}</span>
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="p-3">
                                        <h6 class="product-title">{{ $product->name }}</h6>

                                        <!-- Product Meta Details -->
                                        <ul class="product-meta mb-3">
                                            <li class="meta-item">
                                                <span class="meta-icon text-primary"><i class="entypo-tag"></i></span>
                                                <span class="meta-label">Price</span>
                                                <span class="meta-value">₱{{ number_format($product->price_per_unit, 2) }} <small class="text-muted">/ {{ $product->unit_type }}</small></span>
                                            </li>
                                            <li class="meta-item">
                                                <span class="meta-icon text-success"><i class="entypo-basket"></i></span>
                                                <span class="meta-label">Stock</span>
                                                <span class="meta-value">{{ $product->stock_quantity }} <small class="text-muted">{{ $product->unit_type }}</small></span>
                                            </li>
                                            <li class="meta-item">
                                                <span class="meta-icon"><i class="entypo-calendar"></i></span>
                                                <span class="meta-label">Harvested</span>
                                                <span class="meta-value">{{ \Carbon\Carbon::parse($product->harvest_date)->format('M d, Y') }}</span>
                                            </li>
                                        </ul>

                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-1">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('farmer.products.show', $product) }}"
                                                   class="btn btn-info btn-sm" title="View Details">
                                                    <i class="entypo-eye"></i>
                                                </a>
                                                <a href="{{ route('farmer.products.edit', $product) }}"
                                                   class="btn btn-warning btn-sm" title="Edit Product">
                                                    <i class="entypo-pencil"></i>
                                                </a>
                                                <form action="{{ route('farmer.products.destroy', $product) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Delete Product"
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="entypo-trash"></i>
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
                            <div class="panel panel-default">
                                <div class="panel-body text-center py-4 py-md-5">
                                    <i class="entypo-box text-muted mb-3 mb-md-4" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mb-2 mb-md-3">No products found</h5>
                                    <p class="text-muted mb-3 mb-md-4 small">Start by adding your first product to showcase your farm's produce.</p>
                                    <a href="{{ route('farmer.products.create') }}" class="btn btn-primary btn-md btn-lg-md">
                                        <i class="entypo-plus"></i> Add Your First Product
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
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="imagePreviewModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" class="img-responsive w-100" alt="Product Image" style="max-height: 70vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Neon Template Styling */
    .panel {
        border-radius: 0;
        border: 1px solid #ddd;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        margin-bottom: 20px;
    }

    .panel-heading {
        padding: 15px 20px;
        border-bottom: 1px solid #ddd;
        background-color: #f8f9fa;
    }

    .panel-title h4, .panel-title h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .panel-title i {
        margin-right: 8px;
    }

    .panel-body {
        padding: 20px;
    }

    .panel-options {
        position: absolute;
        right: 20px;
        top: 15px;
    }

    /* Form Controls */
    .form-control {
        border-radius: 0;
        border: 1px solid #ddd;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #359ade;
        box-shadow: 0 0 0 0.2rem rgba(53, 154, 222, 0.25);
        outline: 0;
    }

    /* Filter Styling */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .control-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    /* Input Groups */
    .input-group-addon {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-right: none;
        font-size: 14px;
        color: #555;
    }

    .input-group .form-control {
        border-left: none;
    }

    /* Buttons */
    .btn {
        border-radius: 0;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid transparent;
        transition: all 0.15s ease-in-out;
    }

    .btn-lg {
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 600;
    }

    .btn-primary {
        background-color: #359ade;
        border-color: #359ade;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(53, 154, 222, 0.3);
    }

    .btn-default {
        background-color: #f8f9fa;
        border-color: #ddd;
        color: #333;
    }

    .btn-default:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #138496;
    }

    .btn-warning {
        background-color: #f39c12;
        border-color: #f39c12;
        color: white;
    }

    .btn-warning:hover {
        background-color: #e67e22;
        border-color: #e67e22;
    }

    .btn-danger {
        background-color: #e74c3c;
        border-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }

    .btn-block {
        display: block;
        width: 100%;
    }


    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .btn-group .btn {
        border-radius: 0 !important;
        padding: 5px 10px;
    }

    /* Product Cards */
    .product-card {
        margin-bottom: 20px;
    }

    .product-card .panel {
        transition: all 0.3s ease;
        height: 100%;
    }

    .card-elevated {
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .product-card .panel:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15) !important;
    }

    .product-image {
        height: 180px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.03);
    }

    .no-image-placeholder {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #ccc;
    }

    .no-image-placeholder i {
        font-size: 2.5rem;
    }

    /* Badge positions */
    .status-badge {
        position: absolute;
        left: 10px;
        top: 10px;
    }
    .category-badge {
        position: absolute;
        right: 10px;
        top: 10px;
    }

    /* Badges */
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 0;
    }

    .badge-success {
        background-color: #00a651;
        color: white;
    }

    .badge-warning {
        background-color: #f39c12;
        color: white;
    }

    .badge-danger {
        background-color: #e74c3c;
        color: white;
    }

    .badge-primary {
        background-color: #359ade;
        color: white;
    }

    /* Position Utilities */
    .position-relative {
        position: relative;
    }

    .position-absolute {
        position: absolute;
    }

    .top-0 {
        top: 0;
    }

    .end-0 {
        right: 0;
    }

    .m-2 {
        margin: 0.5rem;
    }

    .p-0 {
        padding: 0;
    }

    .p-3 {
        padding: 1rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mt-md-5 {
        margin-top: 3rem;
    }

    .py-4 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .py-md-5 {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    /* Text Utilities */
    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #6c757d;
    }

    .text-primary {
        color: #359ade;
    }

    .text-success {
        color: #00a651;
    }

    .small {
        font-size: 0.875em;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: .5rem;
    }

    /* Product Meta List */
    .product-meta {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .product-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        border-top: 1px dashed #eee;
    }
    .product-meta .meta-item:first-child {
        border-top: 0;
        padding-top: 0;
    }
    .product-meta .meta-icon {
        width: 18px;
        text-align: center;
        color: #adb5bd;
    }
    .product-meta .meta-label {
        color: #6c757d;
        font-size: 0.85rem;
        min-width: 70px;
    }
    .product-meta .meta-value {
        font-weight: 600;
        color: #333;
    }

    /* Status Counter Cards */
    .status-counter-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .status-counter-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .status-counter-card.active {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }

    .status-count {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    /* Form Controls Alignment */
    .form-control, .btn {
        height: 34px;
        line-height: 1.42857143;
    }

    .input-group .form-control {
        height: 34px;
    }

    .input-group-addon {
        height: 34px;
        line-height: 1.42857143;
    }

    /* Border Utilities */
    .border-end {
        border-right: 1px solid #dee2e6;
    }

    /* Grid Utilities */
    .d-grid {
        display: grid;
    }

    .gap-1 {
        gap: 0.25rem;
    }

    .d-inline {
        display: inline;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-center {
        justify-content: center;
    }

    .w-100 {
        width: 100%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .panel-body { padding: 15px; }
        .panel-heading { padding: 15px; }
        .panel-options { position: static; margin-top: 10px; }
        .product-image { height: 140px !important; }
        .no-image-placeholder { height: 140px !important; }
        .status-badge, .category-badge { top: 6px; }

        /* Filter responsive adjustments */
        .form-group { margin-bottom: 10px; }
        .col-sm-12 .form-group:last-child { margin-bottom: 0; }

        /* Button responsive adjustments */
        .text-left { text-align: center !important; }
        .btn-lg { padding: 10px 20px; font-size: 15px; }
    }

    @media (max-width: 576px) {
        .panel-body { padding: 12px; }
        .panel-heading { padding: 12px; }
        .product-image { height: 120px !important; }
        .no-image-placeholder { height: 120px !important; }

        /* Filter mobile adjustments */
        .form-group { margin-bottom: 8px; }
        .control-label { font-size: 12px; }
        .form-control { font-size: 13px; padding: 8px 10px; }
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

    function updateStatusCounters() {
        var statusCounts = {
            'available': 0,
            'out_of_stock': 0,
            'unavailable': 0
        };

        $('.product-card').each(function() {
            var status = $(this).data('status');
            if (statusCounts.hasOwnProperty(status)) {
                statusCounts[status]++;
            }
        });

        // Update counter displays
        $('.status-counter-card[data-status="available"] .status-count').text(statusCounts.available);
        $('.status-counter-card[data-status="out_of_stock"] .status-count').text(statusCounts.out_of_stock);
        $('.status-counter-card[data-status="unavailable"] .status-count').text(statusCounts.unavailable);
    }

    function filterProducts() {
        var searchValue = $('#searchInput').val().toLowerCase().trim();
        var categoryValue = $('#categoryFilter').val();
        var statusValue = $('.status-counter-card.active').data('status'); // Get active status filter

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
                        <div class="panel panel-default">
                            <div class="panel-body text-center py-4">
                                <i class="entypo-search text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mb-2">No products found</h5>
                                <p class="text-muted mb-3 small">Try adjusting your search criteria or filters.</p>
                                <button class="btn btn-outline-primary btn-sm" id="resetSearch">
                                    <i class="entypo-cancel"></i> Reset Search
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        } else {
            $('#noResultsMessage').remove();
        }

        // Update result counter
        $('#resultCount').text(visibleCount + ' of ' + totalCount + ' results');
    }

    // Bind filter events with debouncing for search
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterProducts, 300);
    });

    $('#categoryFilter').on('change', filterProducts);

    // Status counter card click handlers
    $('.status-counter-card').on('click', function() {
        var status = $(this).data('status');
        $('.status-counter-card').removeClass('active');
        $(this).addClass('active');
        filterProducts();
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('.status-counter-card').removeClass('active');
        $('.product-card').show();
        $('#noResultsMessage').remove();
    });

    // Reset search button
    $(document).on('click', '#resetSearch', function() {
        $('#clearFilters').click();
    });

    // Initialize counters and filter
    updateStatusCounters();
    filterProducts();
});
</script>
@endpush
