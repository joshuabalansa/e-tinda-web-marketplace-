@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-pencil"></i> Edit Product</h4>
                </div>
                <div class="panel-options">
                    <a href="{{ route('farmer.products.index') }}" class="btn btn-default btn-sm">
                        <i class="entypo-left-open"></i> Back to Products
                    </a>
                </div>
            </div>
            <div class="panel-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="entypo-attention"></i>
                        <strong>Please correct the following errors:</strong>
                        <ul class="mb-0 mt-2 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h5><i class="entypo-pencil"></i> Product Information</h5>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form action="{{ route('farmer.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Product Name -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="control-label">Product Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                       id="name" name="name" value="{{ old('name', $product->name) }}"
                                                       placeholder="Enter product name" required>
                                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Category -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category" class="control-label">Category</label>
                                                <select class="form-control @error('category') is-invalid @enderror"
                                                        id="category" name="category" required>
                                                    <option value="">Select a category</option>
                                                    <option value="Vegetables" {{ old('category', $product->category) == 'Vegetables' ? 'selected' : '' }}>🥬 Vegetables</option>
                                                    <option value="Fruits" {{ old('category', $product->category) == 'Fruits' ? 'selected' : '' }}>🍎 Fruits</option>
                                                    <option value="Grains" {{ old('category', $product->category) == 'Grains' ? 'selected' : '' }}>🌾 Grains</option>
                                                    <option value="Dairy" {{ old('category', $product->category) == 'Dairy' ? 'selected' : '' }}>🥛 Dairy</option>
                                                    <option value="Meat" {{ old('category', $product->category) == 'Meat' ? 'selected' : '' }}>🥩 Meat</option>
                                                    <option value="Other" {{ old('category', $product->category) == 'Other' ? 'selected' : '' }}>📦 Other</option>
                                                </select>
                                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description" class="control-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror"
                                                          id="description" name="description" rows="4"
                                                          placeholder="Describe your product (optional)">{{ old('description', $product->description) }}</textarea>
                                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Price and Unit -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price_per_unit" class="control-label">Price Per Unit</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">₱</span>
                                                    <input type="number" step="0.01"
                                                           class="form-control @error('price_per_unit') is-invalid @enderror"
                                                           id="price_per_unit" name="price_per_unit"
                                                           value="{{ old('price_per_unit', $product->price_per_unit) }}"
                                                           placeholder="0.00" required>
                                                </div>
                                                @error('price_per_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Unit Type -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="unit_type" class="control-label">Unit Type</label>
                                                <select class="form-control @error('unit_type') is-invalid @enderror"
                                                        id="unit_type" name="unit_type" required>
                                                    <option value="">Select unit type</option>
                                                    <option value="kg" {{ old('unit_type', $product->unit_type) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                    <option value="g" {{ old('unit_type', $product->unit_type) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                                    <option value="lb" {{ old('unit_type', $product->unit_type) == 'lb' ? 'selected' : '' }}>Pound (lb)</option>
                                                    <option value="piece" {{ old('unit_type', $product->unit_type) == 'piece' ? 'selected' : '' }}>Piece</option>
                                                    <option value="dozen" {{ old('unit_type', $product->unit_type) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                                </select>
                                                @error('unit_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Stock Quantity -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock_quantity" class="control-label">Stock Quantity</label>
                                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                                       id="stock_quantity" name="stock_quantity"
                                                       value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                                       placeholder="Enter quantity" required>
                                                @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Harvest Date -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="harvest_date" class="control-label">Harvest Date</label>
                                                <input type="date" class="form-control @error('harvest_date') is-invalid @enderror"
                                                       id="harvest_date" name="harvest_date"
                                                       value="{{ old('harvest_date', optional($product->harvest_date)->format('Y-m-d')) }}">
                                                @error('harvest_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Product Image -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="image_url" class="control-label">Product Image</label>

                                                @if($product->image_url)
                                                    <div class="current-image mb-3">
                                                        <p class="small text-muted mb-2">Current Image:</p>
                                                        <div class="position-relative d-inline-block">
                                                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                                                 alt="{{ $product->name }}"
                                                                 class="img-responsive"
                                                                 style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                            <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                                    id="removeCurrentImage"
                                                                    title="Remove current image">
                                                                <i class="entypo-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="upload-area">
                                                    <div class="upload-content">
                                                        <i class="entypo-upload"></i>
                                                        <p>{{ $product->image_url ? 'Click to replace image' : 'Click to upload or drag and drop' }}</p>
                                                        @php($maxMb = (int) round(config('upload.max_image_size', 10 * 1024 * 1024) / (1024 * 1024)))
                                                        <small class="text-muted">PNG, JPG, JPEG, WEBP up to {{ $maxMb }}MB</small>
                                                    </div>
                                                    <div class="preview-area" style="display: none;">
                                                        <img src="" alt="Preview" class="img-responsive">
                                                        <button type="button" class="btn btn-sm btn-default mt-2" id="removeImage">
                                                            <i class="entypo-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                    <input type="file" id="image_url" name="image_url" accept="image/*" class="d-none @error('image_url') is-invalid @enderror">
                                                </div>
                                                @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status" class="control-label">Status</label>
                                                <select class="form-control @error('status') is-invalid @enderror"
                                                        id="status" name="status" required>
                                                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>✅ Available</option>
                                                    <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>❌ Unavailable</option>
                                                    <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>⚠️ Out of Stock</option>
                                                </select>
                                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="form-group" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="{{ route('farmer.products.index') }}" class="btn btn-default btn-block">
                                                    <i class="entypo-cancel"></i> Cancel
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-warning btn-block">
                                                    <i class="entypo-check"></i> Update Product
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Product Stats Sidebar -->
                    <div class="col-lg-4">
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading bg-info">
                                <div class="panel-title">
                                    <h6 class="text-white"><i class="entypo-info"></i> Product Stats</h6>
                                </div>
                            </div>
                            <div class="panel-body">
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
                                    <span class="badge float-end {{ $product->status === 'available' ? 'badge-success' : ($product->status === 'out_of_stock' ? 'badge-warning' : 'badge-danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading bg-warning">
                                <div class="panel-title">
                                    <h6 class="text-dark"><i class="entypo-lightbulb"></i> Update Tips</h6>
                                </div>
                            </div>
                            <div class="panel-body">
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

    .panel-title h4, .panel-title h5, .panel-title h6 {
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

    /* Form Styling */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

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

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 12px;
        color: #dc3545;
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

    .btn-block {
        display: block;
        width: 100%;
    }

    /* Upload Area */
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 0;
        padding: 40px 20px;
        text-align: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #359ade;
        background-color: #f0f8ff;
    }

    .upload-content i {
        font-size: 48px;
        color: #999;
        margin-bottom: 15px;
        display: block;
    }

    .upload-content p {
        margin-bottom: 10px;
        font-size: 16px;
        color: #666;
    }

    .preview-area {
        text-align: center;
    }

    .preview-area img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 0;
        margin-bottom: 15px;
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

    /* Background Colors */
    .bg-info {
        background-color: #359ade !important;
    }

    .bg-warning {
        background-color: #f39c12 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .panel-body { padding: 15px; }
        .panel-heading { padding: 15px; }
        .panel-options { position: static; margin-top: 10px; }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    const dropzone = document.querySelector('.upload-area');
    const input = document.getElementById('image_url');
    const previewArea = document.querySelector('.preview-area');
    const uploadContent = document.querySelector('.upload-content');
    const previewImage = document.querySelector('.preview-area img');
    const clearBtn = document.getElementById('removeImage');

    // Click to upload
    dropzone.addEventListener('click', () => input.click());

    // File selection
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                uploadContent.style.display = 'none';
                previewArea.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Clear image
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            input.value = '';
            uploadContent.style.display = 'block';
            previewArea.style.display = 'none';
        });
    }

    // Remove current image
    const removeCurrentBtn = document.getElementById('removeCurrentImage');
    if (removeCurrentBtn) {
        removeCurrentBtn.addEventListener('click', function() {
            $(this).closest('.current-image').fadeOut();
        });
    }

    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#359ade';
        this.style.backgroundColor = '#f0f8ff';
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#ddd';
        this.style.backgroundColor = '#f8f9fa';
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#ddd';
        this.style.backgroundColor = '#f8f9fa';

        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            input.files = files;
            input.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endpush