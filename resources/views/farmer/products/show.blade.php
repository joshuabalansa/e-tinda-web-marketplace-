@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-info"></i> {{ $product->name }}</h4>
                </div>
                <div class="panel-options">
                    <div class="d-flex gap-2">
                        <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-warning btn-sm">
                            <i class="entypo-pencil"></i> Edit Product
                        </a>
                        <a href="{{ route('farmer.products.index') }}" class="btn btn-default btn-sm">
                            <i class="entypo-left-open"></i> Back to Products
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Product Image and Basic Info -->
                    <div class="col-lg-5">
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-body p-0">
                                <!-- Product Image -->
                                <div class="position-relative">
                                    @if($product->image_url)
                                        <img src="{{ asset('storage/' . $product->image_url) }}"
                                             alt="{{ $product->name }}"
                                             class="img-responsive w-100"
                                             style="height: 300px; object-fit: cover; cursor: pointer;"
                                             data-toggle="modal"
                                             data-target="#imageModal">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="height: 300px;">
                                            <div class="text-center">
                                                <i class="entypo-image text-muted mb-3" style="font-size: 3rem;"></i>
                                                <p class="text-muted mb-0 small">No image available</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge {{ $product->status === 'available' ? 'badge-success' : ($product->status === 'out_of_stock' ? 'badge-warning' : 'badge-danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Product Title and Category -->
                                <div class="p-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h2 class="h5 h-md-4 mb-2 fw-bold">{{ $product->name }}</h2>
                                            <span class="badge badge-primary">
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
                                                <i class="entypo-align-left me-2 text-muted"></i>
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
                        <div class="row">
                            <!-- Product Information Card -->
                            <div class="col-12">
                                <div class="panel panel-default" data-collapsed="0">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5><i class="entypo-info"></i> Product Information</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="control-label fw-semibold text-muted mb-1 small">
                                                        <i class="entypo-calendar me-1"></i>
                                                        Harvest Date
                                                    </label>
                                                    <p class="h6 mb-0 small">{{ \Carbon\Carbon::parse($product->harvest_date)->format('F j, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="control-label fw-semibold text-muted mb-1 small">
                                                        <i class="entypo-ruler me-1"></i>
                                                        Unit Type
                                                    </label>
                                                    <p class="h6 mb-0 small">{{ ucfirst($product->unit_type) }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="control-label fw-semibold text-muted mb-1 small">
                                                        <i class="entypo-plus me-1"></i>
                                                        Created Date
                                                    </label>
                                                    <p class="h6 mb-0 small">{{ $product->created_at->format('F j, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="control-label fw-semibold text-muted mb-1 small">
                                                        <i class="entypo-clock me-1"></i>
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
                                <div class="panel panel-default bg-primary text-white" data-collapsed="0">
                                    <div class="panel-body text-center p-3 p-md-4">
                                        <i class="entypo-paper-plane mb-2" style="font-size: 1.5rem;"></i>
                                        <h5 class="mb-1">₱{{ number_format($product->price_per_unit, 2) }}</h5>
                                        <small class="opacity-75 small">Price per {{ $product->unit_type }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default bg-success text-white" data-collapsed="0">
                                    <div class="panel-body text-center p-3 p-md-4">
                                        <i class="entypo-box mb-2" style="font-size: 1.5rem;"></i>
                                        <h5 class="mb-1">{{ $product->stock_quantity }}</h5>
                                        <small class="opacity-75 small">{{ $product->unit_type }} in stock</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default bg-info text-white" data-collapsed="0">
                                    <div class="panel-body text-center p-3 p-md-4">
                                        <i class="entypo-calendar mb-2" style="font-size: 1.5rem;"></i>
                                        <h5 class="mb-1">{{ \Carbon\Carbon::parse($product->harvest_date)->diffForHumans() }}</h5>
                                        <small class="opacity-75 small">Harvest date</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Card -->
                            <div class="col-12">
                                <div class="panel panel-default" data-collapsed="0">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5><i class="entypo-cogs"></i> Actions</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="d-flex flex-column flex-md-row flex-wrap gap-2 gap-md-3">
                                            <a href="{{ route('farmer.products.edit', $product) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="entypo-pencil"></i> Edit Product
                                            </a>
                                            <a href="{{ route('farmer.products.index') }}"
                                               class="btn btn-default btn-sm">
                                                <i class="entypo-left-open"></i> Back to Products
                                            </a>
                                            <form action="{{ route('farmer.products.destroy', $product) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="entypo-trash"></i> Delete Product
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product History/Timeline -->
                            <div class="col-12">
                                <div class="panel panel-default" data-collapsed="0">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5><i class="entypo-clock"></i> Product Timeline</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
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
        </div>
    </div>
</div>

<!-- Image Modal -->
@if($product->image_url)
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="imageModalLabel">{{ $product->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <img src="{{ asset('storage/' . $product->image_url) }}"
                         alt="{{ $product->name }}"
                         class="img-responsive w-100"
                         style="max-height: 70vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
    /* Neon Template Styling */
    .panel {
        border-radius: 0;
        border: 1px solid #ddd;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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

    /* Buttons */
    .btn {
        border-radius: 0;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid transparent;
        transition: all 0.15s ease-in-out;
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

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
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

    /* Info Items */
    .info-item {
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0;
        border-left: 4px solid #359ade;
        margin-bottom: 15px;
    }

    /* Timeline */
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

    /* Badges */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
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

    /* Background Colors */
    .bg-primary {
        background-color: #359ade !important;
    }

    .bg-success {
        background-color: #00a651 !important;
    }

    .bg-info {
        background-color: #359ade !important;
    }

    .bg-warning {
        background-color: #f39c12 !important;
    }

    /* Utilities */
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

    .p-md-4 {
        padding: 1.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-1 {
        margin-bottom: 0.25rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mb-md-4 {
        margin-bottom: 1.5rem;
    }

    .me-1 {
        margin-right: 0.25rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

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

    .text-dark {
        color: #343a40;
    }

    .small {
        font-size: 0.875em;
    }

    .h5 {
        font-size: 1.25rem;
    }

    .h6 {
        font-size: 1rem;
    }

    .h-md-4 {
        font-size: 1.5rem;
    }

    .fw-bold {
        font-weight: 700;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .opacity-75 {
        opacity: 0.75;
    }

    .d-flex {
        display: flex;
    }

    .flex-column {
        flex-direction: column;
    }

    .flex-md-row {
        flex-direction: row;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .d-inline {
        display: inline;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-md-3 {
        gap: 1rem;
    }

    .w-100 {
        width: 100%;
    }

    .border-end {
        border-right: 1px solid #dee2e6;
    }

    .bg-light {
        background-color: #f8f9fa;
    }

    .align-items-center {
        align-items: center;
    }

    .justify-content-center {
        justify-content: center;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-start {
        align-items: flex-start;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .panel-body { padding: 15px; }
        .panel-heading { padding: 15px; }
        .panel-options { position: static; margin-top: 10px; }
        .h-md-4 { font-size: 1.25rem; }
        .p-md-4 { padding: 1rem; }
        .mb-md-4 { margin-bottom: 1rem; }
        .flex-md-row { flex-direction: column; }
        .gap-md-3 { gap: 0.5rem; }
    }

    @media (max-width: 576px) {
        .panel-body { padding: 12px; }
        .panel-heading { padding: 12px; }
        .btn { font-size: 0.8rem; padding: 0.375rem 0.75rem; }
        .h-md-4 { font-size: 1.125rem; }
        .p-md-4 { padding: 0.75rem; }
        .mb-md-4 { margin-bottom: 0.75rem; }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Add hover effects to panels
    $('.panel').addClass('hover-zoom');

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