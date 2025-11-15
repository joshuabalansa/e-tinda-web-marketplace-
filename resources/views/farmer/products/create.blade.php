@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-plus"></i> Add New Product</h4>
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

                <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Product Details Section -->
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5><i class="entypo-tag"></i> Product Information</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}"
                                               placeholder="e.g., Fresh Tomatoes" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category" class="control-label">Category <span class="text-danger">*</span></label>
                                        <select id="category" name="category" class="form-control @error('category') is-invalid @enderror" required>
                                            <option value="">Select Category</option>
                                            <option value="Vegetables" {{ old('category') == 'Vegetables' ? 'selected' : '' }}>🥬 Vegetables</option>
                                            <option value="Fruits" {{ old('category') == 'Fruits' ? 'selected' : '' }}>🍎 Fruits</option>
                                            <option value="Grains" {{ old('category') == 'Grains' ? 'selected' : '' }}>🌾 Grains</option>
                                            <option value="Dairy" {{ old('category') == 'Dairy' ? 'selected' : '' }}>🥛 Dairy</option>
                                            <option value="Meat" {{ old('category') == 'Meat' ? 'selected' : '' }}>🥩 Meat</option>
                                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>📦 Other</option>
                                        </select>
                                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea id="description" name="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Describe your product...">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Units Section -->
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5><i class="entypo-paper-plane"></i> Pricing & Units</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_per_unit" class="control-label">Price Per Unit <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">₱</span>
                                            <input type="number" step="0.01" id="price_per_unit" name="price_per_unit"
                                                   value="{{ old('price_per_unit') }}"
                                                   class="form-control @error('price_per_unit') is-invalid @enderror"
                                                   placeholder="0.00" required>
                                        </div>
                                        @error('price_per_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_type" class="control-label">Unit Type <span class="text-danger">*</span></label>
                                        <select id="unit_type" name="unit_type" class="form-control @error('unit_type') is-invalid @enderror" required>
                                            <option value="">Select Unit</option>
                                            <option value="kg" {{ old('unit_type') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                            <option value="g" {{ old('unit_type') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                            <option value="lb" {{ old('unit_type') == 'lb' ? 'selected' : '' }}>Pound (lb)</option>
                                            <option value="piece" {{ old('unit_type') == 'piece' ? 'selected' : '' }}>Piece</option>
                                            <option value="dozen" {{ old('unit_type') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                        </select>
                                        @error('unit_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Dates Section -->
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5><i class="entypo-box"></i> Inventory & Dates</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock_quantity" class="control-label">Stock Quantity <span class="text-danger">*</span></label>
                                        <input type="number" id="stock_quantity" name="stock_quantity"
                                               value="{{ old('stock_quantity') }}"
                                               class="form-control @error('stock_quantity') is-invalid @enderror"
                                               placeholder="e.g., 100" required>
                                        @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="harvest_date" class="control-label">Harvest Date <span class="text-danger">*</span></label>
                                        <input type="date" id="harvest_date" name="harvest_date"
                                               value="{{ old('harvest_date') }}"
                                               class="form-control @error('harvest_date') is-invalid @enderror" required>
                                        @error('harvest_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5><i class="entypo-camera"></i> Product Image</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="image_url" class="control-label">Product Image</label>
                                <div id="dropzone" class="upload-area">
                                    <div class="upload-content">
                                        <i class="entypo-upload"></i>
                                        <p>Drag & drop image here or click to browse</p>
                                        @php($maxMb = (int) round(config('upload.max_image_size', 10 * 1024 * 1024) / (1024 * 1024)))
                                        <small class="text-muted">PNG, JPG, JPEG, WEBP up to {{ $maxMb }}MB</small>
                                    </div>
                                    <div class="preview-area" style="display: none;">
                                        <img id="previewImage" src="" alt="Preview" class="img-responsive">
                                        <button type="button" id="clearImageBtn" class="btn btn-default btn-sm">
                                            <i class="entypo-trash"></i> Clear
                                        </button>
                                    </div>
                                    <input type="file" id="image_url" name="image_url" accept="image/*"
                                           class="d-none @error('image_url') is-invalid @enderror">
                                </div>
                                @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Availability Section -->
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5><i class="entypo-check"></i> Availability</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="status" class="control-label">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>✅ Available</option>
                                    <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>❌ Unavailable</option>
                                    <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>⚠️ Out of Stock</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('farmer.products.index') }}" class="btn btn-default btn-block">
                                        <i class="entypo-cancel"></i> Cancel
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="entypo-check"></i> Create Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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

    .btn-success {
        background-color: #00a651;
        border-color: #00a651;
        color: white;
    }

    .btn-success:hover {
        background-color: #008f45;
        border-color: #008f45;
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

    /* Alerts */
    .alert {
        border-radius: 0;
        border: 1px solid transparent;
        padding: 15px 20px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert i {
        margin-right: 8px;
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
    const dropzone = document.getElementById('dropzone');
    const input = document.getElementById('image_url');
    const previewArea = document.querySelector('.preview-area');
    const uploadContent = document.querySelector('.upload-content');
    const previewImage = document.getElementById('previewImage');
    const clearBtn = document.getElementById('clearImageBtn');

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
    clearBtn.addEventListener('click', function() {
        input.value = '';
        uploadContent.style.display = 'block';
        previewArea.style.display = 'none';
    });

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
