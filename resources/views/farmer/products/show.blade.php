@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
        <div>
            <h1 class="h4 h-md-3 mb-1 mb-md-2 text-dark fw-bold">{{ $product->name }}</h1>
            <p class="text-muted small mb-0">Product Details & Information</p>
        </div>
        <div class="d-flex flex-column flex-md-row gap-2">
            <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-2"></i> Edit Product
            </a>
            <a href="{{ route('farmer.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <!-- Product Image and Basic Info -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <!-- Product Image -->
                    <div class="position-relative">
                        @if($product->image_url)
                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid w-100 rounded-top"
                                 style="height: 300px; object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal"
                                 data-bs-target="#imageModal">
                        @else
                            <div class="bg-light rounded-top d-flex align-items-center justify-content-center"
                                 style="height: 300px;">
                                <div class="text-center">
                                    <i class="fas fa-image text-muted mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0 small">No image available</p>
                                </div>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge fs-7 px-2 py-1 {{ $product->status === 'available' ? 'bg-success' : ($product->status === 'out_of_stock' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Product Title and Category -->
                    <div class="p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="h5 h-md-4 mb-2 fw-bold">{{ $product->name }}</h2>
                                <span class="badge bg-primary-subtle text-primary fs-7 px-2 py-1">
                                    {{ $product->category }}
                                </span>
                            </div>
                        </div>

                        <!-- Price and Stock -->
                        <div class="row text-center mb-3 mb-md-4">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1 fw-bold">₱{{ number_format($product->price_per_unit, 2) }}</h4>
                                    <small class="text-muted small">per {{ $product->unit_type }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1 fw-bold">{{ $product->stock_quantity }}</h4>
                                <small class="text-muted small">{{ $product->unit_type }} available</small>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($product->description)
                            <div class="mb-3 mb-md-4">
                                <h6 class="fw-semibold text-dark mb-2">
                                    <i class="fas fa-align-left me-2 text-muted"></i>
                                    Description
                                </h6>
                                <p class="text-muted mb-0 small">{{ $product->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details and Stats -->
        <div class="col-lg-7">
            <div class="row g-3 g-md-4">
                <!-- Product Information Card -->
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 py-md-4">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                Product Information
                            </h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="row g-3 g-md-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted mb-1 small">
                                            <i class="fas fa-calendar me-1"></i>
                                            Harvest Date
                                        </label>
                                        <p class="h6 mb-0 small">{{ \Carbon\Carbon::parse($product->harvest_date)->format('F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted mb-1 small">
                                            <i class="fas fa-ruler me-1"></i>
                                            Unit Type
                                        </label>
                                        <p class="h6 mb-0 small">{{ ucfirst($product->unit_type) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted mb-1 small">
                                            <i class="fas fa-plus me-1"></i>
                                            Created Date
                                        </label>
                                        <p class="h6 mb-0 small">{{ $product->created_at->format('F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted mb-1 small">
                                            <i class="fas fa-clock me-1"></i>
                                            Last Updated
                                        </label>
                                        <p class="h6 mb-0 small">{{ $product->updated_at->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Cards -->
                <div class="col-md-4">
                    <div class="card shadow-sm bg-primary text-white border-0">
                        <div class="card-body text-center p-3 p-md-4">
                            <i class="fas fa-peso-sign mb-2" style="font-size: 1.5rem;"></i>
                            <h5 class="mb-1">₱{{ number_format($product->price_per_unit, 2) }}</h5>
                            <small class="opacity-75 small">Price per {{ $product->unit_type }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm bg-success text-white border-0">
                        <div class="card-body text-center p-3 p-md-4">
                            <i class="fas fa-box mb-2" style="font-size: 1.5rem;"></i>
                            <h5 class="mb-1">{{ $product->stock_quantity }}</h5>
                            <small class="opacity-75 small">{{ $product->unit_type }} in stock</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm bg-info text-white border-0">
                        <div class="card-body text-center p-3 p-md-4">
                            <i class="fas fa-calendar mb-2" style="font-size: 1.5rem;"></i>
                            <h5 class="mb-1">{{ \Carbon\Carbon::parse($product->harvest_date)->diffForHumans() }}</h5>
                            <small class="opacity-75 small">Harvest date</small>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 py-md-4">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-cogs text-secondary me-2"></i>
                                Actions
                            </h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column flex-md-row flex-wrap gap-2 gap-md-3">
                                <a href="{{ route('farmer.products.edit', $product) }}"
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-2"></i> Edit Product
                                </a>
                                <a href="{{ route('farmer.products.index') }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Products
                                </a>
                                <form action="{{ route('farmer.products.destroy', $product) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-2"></i> Delete Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product History/Timeline (Future Enhancement) -->
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 py-md-4">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Product Timeline
                            </h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1 small">Product Created</h6>
                                        <p class="text-muted mb-0 small">{{ $product->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                @if($product->updated_at != $product->created_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1 small">Last Updated</h6>
                                            <p class="text-muted mb-0 small">{{ $product->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1 small">Harvest Date</h6>
                                        <p class="text-muted mb-0 small">{{ \Carbon\Carbon::parse($product->harvest_date)->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
@if($product->image_url)
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="imageModalLabel">{{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <img src="{{ asset('storage/' . $product->image_url) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid w-100"
                         style="max-height: 70vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
    .card {
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .75rem;
        overflow: hidden;
    }
    .btn {
        border-radius: .5rem;
        font-size: 0.875rem;
    }
    .form-control, .form-select {
        border-radius: .5rem;
        font-size: 0.875rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .info-item {
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: .5rem;
        border-left: 4px solid #0d6efd;
    }
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: .5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -1.5rem;
        top: .25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }
    .timeline-content {
        padding-left: 1rem;
    }
    .hover-zoom {
        transition: transform .25s ease;
    }
    .hover-zoom:hover {
        transform: scale(1.02);
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Mobile responsive utilities */
    .h-md-3 { font-size: 1.75rem; }
    .h-md-4 { font-size: 1.5rem; }
    .fs-7 { font-size: 0.75rem; }

    @media (max-width: 768px) {
        .h-md-3 { font-size: 1.5rem; }
        .h-md-4 { font-size: 1.25rem; }
        .card-body { padding: 1rem; }
        .card-header { padding: 1rem; }
        .info-item { padding: 0.5rem; }
    }

    @media (max-width: 576px) {
        .h-md-3 { font-size: 1.25rem; }
        .h-md-4 { font-size: 1.125rem; }
        .card-body { padding: 0.75rem; }
        .card-header { padding: 0.75rem; }
        .btn { font-size: 0.8rem; padding: 0.375rem 0.75rem; }
        .info-item { padding: 0.5rem; }
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
    // Add hover effects to cards
    $('.card').addClass('hover-zoom');

    // Animate timeline items on scroll
    $(window).on('scroll', function() {
        $('.timeline-item').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate__animated animate__fadeInLeft');
            }
        });
    });
});
</script>
@endpush
@endsection