@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
        <div>
            <h1 class="h4 h-md-3 mb-1 mb-md-2 text-dark fw-bold">Edit Product</h1>
            <p class="text-muted small mb-0">Update your product information</p>
        </div>
        <a href="{{ route('farmer.products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3 mb-md-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 g-md-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 py-md-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Product Information
                    </h5>
                </div>

                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('farmer.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 g-md-4">
                            <!-- Product Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-tag me-1 text-muted"></i>
                                    Product Name
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $product->name) }}"
                                       placeholder="Enter product name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold">
                                    <i class="fas fa-folder me-1 text-muted"></i>
                                    Category
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category"
                                        name="category"
                                        required>
                                    <option value="">Select a category</option>
                                    <option value="Vegetables" {{ old('category', $product->category) == 'Vegetables' ? 'selected' : '' }}>🥬 Vegetables</option>
                                    <option value="Fruits" {{ old('category', $product->category) == 'Fruits' ? 'selected' : '' }}>🍎 Fruits</option>
                                    <option value="Grains" {{ old('category', $product->category) == 'Grains' ? 'selected' : '' }}>🌾 Grains</option>
                                    <option value="Dairy" {{ old('category', $product->category) == 'Dairy' ? 'selected' : '' }}>🥛 Dairy</option>
                                    <option value="Meat" {{ old('category', $product->category) == 'Meat' ? 'selected' : '' }}>🥩 Meat</option>
                                    <option value="Other" {{ old('category', $product->category) == 'Other' ? 'selected' : '' }}>📦 Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-align-left me-1 text-muted"></i>
                                    Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Describe your product (optional)">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price and Unit -->
                            <div class="col-md-6">
                                <label for="price_per_unit" class="form-label fw-semibold">
                                    <i class="fas fa-peso-sign me-1 text-muted"></i>
                                    Price Per Unit
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">₱</span>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control @error('price_per_unit') is-invalid @enderror"
                                           id="price_per_unit"
                                           name="price_per_unit"
                                           value="{{ old('price_per_unit', $product->price_per_unit) }}"
                                           placeholder="0.00"
                                           required>
                                    @error('price_per_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Unit Type -->
                            <div class="col-md-6">
                                <label for="unit_type" class="form-label fw-semibold">
                                    <i class="fas fa-ruler me-1 text-muted"></i>
                                    Unit Type
                                </label>
                                <select class="form-select @error('unit_type') is-invalid @enderror"
                                        id="unit_type"
                                        name="unit_type"
                                        required>
                                    <option value="">Select unit type</option>
                                    <option value="kg" {{ old('unit_type', $product->unit_type) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="g" {{ old('unit_type', $product->unit_type) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                    <option value="lb" {{ old('unit_type', $product->unit_type) == 'lb' ? 'selected' : '' }}>Pound (lb)</option>
                                    <option value="piece" {{ old('unit_type', $product->unit_type) == 'piece' ? 'selected' : '' }}>Piece</option>
                                    <option value="dozen" {{ old('unit_type', $product->unit_type) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                </select>
                                @error('unit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock Quantity -->
                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label fw-semibold">
                                    <i class="fas fa-box me-1 text-muted"></i>
                                    Stock Quantity
                                </label>
                                <input type="number"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity"
                                       name="stock_quantity"
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                       placeholder="Enter quantity"
                                       required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Harvest Date -->
                            <div class="col-md-6">
                                <label for="harvest_date" class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1 text-muted"></i>
                                    Harvest Date
                                </label>
                                <input type="date"
                                       class="form-control @error('harvest_date') is-invalid @enderror"
                                       id="harvest_date"
                                       name="harvest_date"
                                       value="{{ old('harvest_date', $product->harvest_date) }}"
                                       required>
                                @error('harvest_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Image -->
                            <div class="col-12">
                                <label for="image_url" class="form-label fw-semibold">
                                    <i class="fas fa-camera me-1 text-muted"></i>
                                    Product Image
                                </label>

                                @if($product->image_url)
                                    <div class="current-image mb-3">
                                        <p class="small text-muted mb-2">Current Image:</p>
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-1"
                                                    id="removeCurrentImage"
                                                    title="Remove current image">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <div class="upload-area border-2 border-dashed rounded-3 p-3 p-md-4 text-center">
                                    <input type="file"
                                           class="form-control d-none @error('image_url') is-invalid @enderror"
                                           id="image_url"
                                           name="image_url"
                                           accept="image/*">
                                    <div class="upload-content">
                                        <i class="fas fa-cloud-upload-alt text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-2 small">{{ $product->image_url ? 'Click to replace image' : 'Click to upload or drag and drop' }}</p>
                                        <small class="text-muted">PNG, JPG, JPEG up to 5MB</small>
                                    </div>
                                    <div class="preview-area d-none">
                                        <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeImage">
                                            <i class="fas fa-trash me-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                @error('image_url')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="fas fa-check-circle me-1 text-muted"></i>
                                    Status
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                        required>
                                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>✅ Available</option>
                                    <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>❌ Unavailable</option>
                                    <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>⚠️ Out of Stock</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-4 mt-md-5 pt-3 pt-md-4 border-top">
                            <a href="{{ route('farmer.products.index') }}" class="btn btn-outline-secondary order-2 order-md-1">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning order-1 order-md-2">
                                <i class="fas fa-check me-2"></i> Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Stats Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3 mb-md-4">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Product Stats
                    </h6>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">₱{{ number_format($product->price_per_unit, 2) }}</h4>
                                <small class="text-muted small">Price/{{ $product->unit_type }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $product->stock_quantity }}</h4>
                            <small class="text-muted small">In Stock</small>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted small">Created:</small>
                        <span class="float-end small">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted small">Last Updated:</small>
                        <span class="float-end small">{{ $product->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <small class="text-muted small">Status:</small>
                        <span class="badge float-end {{ $product->status === 'available' ? 'bg-success' : ($product->status === 'out_of_stock' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0 fw-bold">
                        <i class="fas fa-lightbulb me-2"></i>
                        Update Tips
                    </h6>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary small">📊 Regular Updates</h6>
                        <p class="small text-muted mb-0">Keep your product information current to maintain customer trust and improve sales.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold text-success small">📈 Price Optimization</h6>
                        <p class="small text-muted mb-0">Monitor market prices and adjust accordingly to stay competitive.</p>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-info small">📦 Stock Management</h6>
                        <p class="small text-muted mb-0">Update stock levels regularly to prevent overselling and maintain availability.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .upload-area {
        border-color: #dee2e6 !important;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.25s ease;
        border-style: dashed !important;
        border-width: 2px !important;
        border-radius: .75rem;
    }
    .upload-area:hover, .upload-area.dragover {
        border-color: #0d6efd !important;
        background-color: #e7f3ff;
    }
    .form-control, .form-select {
        border-radius: .5rem;
        font-size: 0.875rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .card {
        border-radius: .75rem;
        overflow: hidden;
    }
    .btn {
        border-radius: .5rem;
        font-size: 0.875rem;
    }
    .input-group-text {
        border-radius: .5rem 0 0 .5rem;
        font-size: 0.875rem;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Mobile responsive utilities */
    .h-md-3 { font-size: 1.75rem; }

    @media (max-width: 768px) {
        .h-md-3 { font-size: 1.5rem; }
        .card-body { padding: 1rem; }
        .card-header { padding: 1rem; }
    }

    @media (max-width: 576px) {
        .h-md-3 { font-size: 1.25rem; }
        .card-body { padding: 0.75rem; }
        .card-header { padding: 0.75rem; }
        .btn { font-size: 0.8rem; padding: 0.375rem 0.75rem; }
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
    // File upload handling
    $('.upload-area').on('click', function() {
        $('#image_url').click();
    });

    $('#image_url').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.preview-area img').attr('src', e.target.result);
                $('.upload-content').addClass('d-none');
                $('.preview-area').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#removeImage').on('click', function() {
        $('#image_url').val('');
        $('.upload-content').removeClass('d-none');
        $('.preview-area').addClass('d-none');
    });

    $('#removeCurrentImage').on('click', function() {
        $(this).closest('.current-image').fadeOut();
        // You might want to add a hidden input to mark for deletion
    });

    // Drag and drop functionality
    $('.upload-area').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $('.upload-area').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $('.upload-area').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');

        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#image_url')[0].files = files;
            $('#image_url').trigger('change');
        }
    });
});
</script>
@endpush
@endsection