@extends('layouts.dashboard')

@push('styles')
<style>
    .section-title {
        letter-spacing: .05em;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .border-dashed { border-style: dashed !important; }

    #dropzone {
        cursor: pointer;
        transition: background-color .2s, border-color .2s;
        border-radius: 0.75rem;
    }
    #dropzone.dropzone-active {
        border-color: #0d6efd !important;
        background-color: #f0f7ff;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }

    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
        font-size: 0.875rem;
    }

    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .btn {
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Mobile responsive utilities */
    @media (max-width: 768px) {
        .card-body { padding: 1rem; }
        .card-header { padding: 1rem; }
        .section-title { font-size: 0.8rem; }
    }

    @media (max-width: 576px) {
        .card-body { padding: 0.75rem; }
        .card-header { padding: 0.75rem; }
        .section-title { font-size: 0.75rem; }
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

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">

    <div class="mb-3 mb-md-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 mb-md-2">
                <li class="breadcrumb-item"><a href="{{ route('farmer.products.index') }}">{{ __('dashboard.products') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard.create') }}</li>
            </ol>
        </nav>
        <h1 class="h4 h-md-3 fw-semibold text-dark mb-1 mb-md-2">{{ __('dashboard.add_product') }}</h1>
        <p class="text-muted small mb-0">{{ __('dashboard.add_new_product') }}</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <div>
                    <h2 class="h5 h-md-4 mb-1 fw-semibold text-dark">{{ __('dashboard.create') }} {{ __('dashboard.products') }}</h2>
                    <span class="text-muted small">{{ __('dashboard.add_new_product') }}</span>
                </div>
                <a href="{{ route('farmer.products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> {{ __('dashboard.back') }}
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-md-4">
            <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 g-md-4">
                @csrf

                <!-- Product details -->
                <div class="col-12">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2 mb-md-3 section-title">{{ __('dashboard.product_details') }}</h6>
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="name" class="form-label">{{ __('dashboard.product_name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., Fresh Tomatoes" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text small">{{ __('dashboard.name_help') }}</div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="category" class="form-label">{{ __('dashboard.category') }} <span class="text-danger">*</span></label>
                    <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">{{ __('dashboard.select_category') }}</option>
                        <option value="Vegetables" {{ old('category') == 'Vegetables' ? 'selected' : '' }}>🥬 {{ __('dashboard.vegetables') }}</option>
                        <option value="Fruits" {{ old('category') == 'Fruits' ? 'selected' : '' }}>🍎 {{ __('dashboard.fruits') }}</option>
                        <option value="Grains" {{ old('category') == 'Grains' ? 'selected' : '' }}>🌾 {{ __('dashboard.grains') }}</option>
                        <option value="Dairy" {{ old('category') == 'Dairy' ? 'selected' : '' }}>🥛 {{ __('dashboard.dairy') }}</option>
                        <option value="Meat" {{ old('category') == 'Meat' ? 'selected' : '' }}>🥩 {{ __('dashboard.meat') }}</option>
                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>📦 {{ __('dashboard.other') }}</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">{{ __('dashboard.description') }}</label>
                    <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('dashboard.description_placeholder') }}">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text small">{{ __('dashboard.description_help') }}</div>
                </div>

                <!-- Pricing & units -->
                <div class="col-12 pt-1 pt-md-2">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2 mb-md-3 section-title">{{ __('dashboard.pricing_units') }}</h6>
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="price_per_unit" class="form-label">{{ __('dashboard.price_per_unit') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" id="price_per_unit" name="price_per_unit" value="{{ old('price_per_unit') }}" class="form-control @error('price_per_unit') is-invalid @enderror" placeholder="0.00" required>
                        @error('price_per_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-text small">{{ __('dashboard.price_help') }}</div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="unit_type" class="form-label">{{ __('dashboard.unit_type') }} <span class="text-danger">*</span></label>
                    <select id="unit_type" name="unit_type" class="form-select @error('unit_type') is-invalid @enderror" required>
                        <option value="">{{ __('dashboard.select_unit') }}</option>
                        <option value="kg" {{ old('unit_type') == 'kg' ? 'selected' : '' }}>{{ __('dashboard.kg') }}</option>
                        <option value="g" {{ old('unit_type') == 'g' ? 'selected' : '' }}>{{ __('dashboard.g') }}</option>
                        <option value="lb" {{ old('unit_type') == 'lb' ? 'selected' : '' }}>{{ __('dashboard.lb') }}</option>
                        <option value="piece" {{ old('unit_type') == 'piece' ? 'selected' : '' }}>{{ __('dashboard.piece') }}</option>
                        <option value="dozen" {{ old('unit_type') == 'dozen' ? 'selected' : '' }}>{{ __('dashboard.dozen') }}</option>
                    </select>
                    @error('unit_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Inventory & dates -->
                <div class="col-12 pt-1 pt-md-2">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2 mb-md-3 section-title">{{ __('dashboard.inventory_dates') }}</h6>
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="stock_quantity" class="form-label">{{ __('dashboard.stock_quantity') }} <span class="text-danger">*</span></label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" class="form-control @error('stock_quantity') is-invalid @enderror" placeholder="e.g., 100" required>
                    @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="harvest_date" class="form-label">{{ __('dashboard.harvest_date') }} <span class="text-danger">*</span></label>
                    <input type="date" id="harvest_date" name="harvest_date" value="{{ old('harvest_date') }}" class="form-control @error('harvest_date') is-invalid @enderror" required>
                    @error('harvest_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Media -->
                <div class="col-12 pt-1 pt-md-2">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2 mb-md-3 section-title">{{ __('dashboard.media') }}</h6>
                </div>

                <div class="col-12">
                    <label for="image_url" class="form-label">{{ __('dashboard.product_image') }}</label>
                    <div id="dropzone" class="border border-2 border-dashed rounded-4 p-3 p-md-4 text-center bg-light">
                        <div class="mb-2 mb-md-3">
                            <img id="previewImage" src="" alt="Preview" class="img-fluid rounded d-none" style="max-height: 180px;">
                        </div>
                        <div class="text-muted small">{{ __('dashboard.drag_drop') }}</div>
                        <div class="mt-2 mt-md-3 d-flex justify-content-center gap-2">
                            <button type="button" id="chooseImageBtn" class="btn btn-outline-primary btn-sm">{{ __('dashboard.choose_image') }}</button>
                            <button type="button" id="clearImageBtn" class="btn btn-outline-secondary btn-sm d-none">{{ __('dashboard.clear_image') }}</button>
                        </div>
                        <input type="file" id="image_url" name="image_url" accept="image/*" class="d-none @error('image_url') is-invalid @enderror">
                    </div>
                    @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-text small">{{ __('dashboard.image_help') }}</div>
                </div>

                <!-- Availability -->
                <div class="col-12 pt-1 pt-md-2">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2 mb-md-3 section-title">{{ __('dashboard.availability') }}</h6>
                </div>

                <div class="col-lg-6 col-md-6">
                    <label for="status" class="form-label">{{ __('dashboard.status') }} <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>✅ {{ __('dashboard.available') }}</option>
                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>❌ {{ __('dashboard.unavailable') }}</option>
                        <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>⚠️ {{ __('dashboard.out_of_stock') }}</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-end gap-2 pt-2 pt-md-3 mt-1 mt-md-2 border-top">
                        <a href="{{ route('farmer.products.index') }}" class="btn btn-outline-secondary order-2 order-md-1">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" class="btn btn-primary order-1 order-md-2">{{ __('dashboard.create') }} {{ __('dashboard.products') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('image_url');
        const dropzone = document.getElementById('dropzone');
        const chooseBtn = document.getElementById('chooseImageBtn');
        const clearBtn = document.getElementById('clearImageBtn');
        const previewImg = document.getElementById('previewImage');

        if (!input || !dropzone) return;

        function setPreview(file) {
            if (!file) {
                previewImg.src = '';
                previewImg.classList.add('d-none');
                clearBtn.classList.add('d-none');
                return;
            }
            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove('d-none');
            clearBtn.classList.remove('d-none');
        }

        function setFilesOnInput(file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            setPreview(file);
        }

        chooseBtn.addEventListener('click', () => input.click());

        input.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                setPreview(file);
            }
        });

        clearBtn.addEventListener('click', function () {
            input.value = '';
            setPreview(null);
        });

        dropzone.addEventListener('click', function () { input.click(); });

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.classList.add('dropzone-active');
        });

        dropzone.addEventListener('dragleave', function () {
            dropzone.classList.remove('dropzone-active');
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('dropzone-active');
            if (!e.dataTransfer || !e.dataTransfer.files || !e.dataTransfer.files.length) return;
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                setFilesOnInput(file);
            }
        });
    });
</script>
@endpush
@endsection
