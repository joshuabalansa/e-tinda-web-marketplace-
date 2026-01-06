@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">{{ __('forums.create_new_topic') }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('forums.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

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

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('forums.title') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">{{ __('forums.category') }}</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">{{ __('forums.select_category') }}</option>
                                <option value="Crop Farming" {{ old('category') == 'Crop Farming' ? 'selected' : '' }}>{{ __('forums.crop_farming') }}</option>
                                <option value="Livestock" {{ old('category') == 'Livestock' ? 'selected' : '' }}>{{ __('forums.livestock') }}</option>
                                <option value="Organic Farming" {{ old('category') == 'Organic Farming' ? 'selected' : '' }}>{{ __('forums.organic_farming') }}</option>
                                <option value="Market Prices" {{ old('category') == 'Market Prices' ? 'selected' : '' }}>{{ __('forums.market_prices') }}</option>
                                <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>{{ __('forums.equipment') }}</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">{{ __('forums.content') }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harvest Calendar Section - Only for Farmers -->
                        @auth
                            @if(auth()->user()->isFarmer())
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_harvest_post" name="is_harvest_post" value="1" {{ old('is_harvest_post') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_harvest_post">
                                            <strong>{{ __('forums.post_to_harvest_calendar') }}</strong>
                                        </label>
                                    </div>
                                    <div class="form-text">{{ __('forums.harvest_calendar_description') }}</div>
                                </div>
                            @endif
                        @endauth

                        <div id="harvest-fields" style="display: none;">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">{{ __('forums.harvest_calendar') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">{{ __('forums.product_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}" placeholder="{{ __('forums.product_name_placeholder') }}">
                                        @error('product_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="harvest_start_date" class="form-label">{{ __('forums.harvest_start_date') }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('harvest_start_date') is-invalid @enderror" id="harvest_start_date" name="harvest_start_date" value="{{ old('harvest_start_date') }}">
                                            @error('harvest_start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="harvest_end_date" class="form-label">{{ __('forums.harvest_end_date') }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('harvest_end_date') is-invalid @enderror" id="harvest_end_date" name="harvest_end_date" value="{{ old('harvest_end_date') }}">
                                            @error('harvest_end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="harvest_season" class="form-label">{{ __('forums.harvest_season') }}</label>
                                            <select class="form-select @error('harvest_season') is-invalid @enderror" id="harvest_season" name="harvest_season">
                                                <option value="">{{ __('forums.harvest_season_select') }}</option>
                                                <option value="Spring" {{ old('harvest_season') == 'Spring' ? 'selected' : '' }}>{{ __('forums.season_spring') }}</option>
                                                <option value="Summer" {{ old('harvest_season') == 'Summer' ? 'selected' : '' }}>{{ __('forums.season_summer') }}</option>
                                                <option value="Fall" {{ old('harvest_season') == 'Fall' ? 'selected' : '' }}>{{ __('forums.season_fall') }}</option>
                                                <option value="Winter" {{ old('harvest_season') == 'Winter' ? 'selected' : '' }}>{{ __('forums.season_winter') }}</option>
                                                <option value="Dry Season" {{ old('harvest_season') == 'Dry Season' ? 'selected' : '' }}>{{ __('forums.season_dry') }}</option>
                                                <option value="Wet Season" {{ old('harvest_season') == 'Wet Season' ? 'selected' : '' }}>{{ __('forums.season_wet') }}</option>
                                            </select>
                                            @error('harvest_season')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="product_category" class="form-label">{{ __('forums.product_category') }}</label>
                                            <select class="form-select @error('product_category') is-invalid @enderror" id="product_category" name="product_category">
                                                <option value="">{{ __('forums.product_category_select') }}</option>
                                                <option value="Vegetables" {{ old('product_category') == 'Vegetables' ? 'selected' : '' }}>{{ __('forums.category_vegetables') }}</option>
                                                <option value="Fruits" {{ old('product_category') == 'Fruits' ? 'selected' : '' }}>{{ __('forums.category_fruits') }}</option>
                                                <option value="Grains" {{ old('product_category') == 'Grains' ? 'selected' : '' }}>{{ __('forums.category_grains') }}</option>
                                                <option value="Livestock" {{ old('product_category') == 'Livestock' ? 'selected' : '' }}>{{ __('forums.category_livestock') }}</option>
                                                <option value="Herbs" {{ old('product_category') == 'Herbs' ? 'selected' : '' }}>{{ __('forums.category_herbs') }}</option>
                                                <option value="Other" {{ old('product_category') == 'Other' ? 'selected' : '' }}>{{ __('forums.category_other') }}</option>
                                            </select>
                                            @error('product_category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">{{ __('forums.image_attachment') }}</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple data-max-size="10485760">
                            <div class="form-text">{{ __('forums.image_help_text') }} (Max: 10MB per image)</div>
                            <div class="form-text text-info">Supported formats: JPG, JPEG, PNG, GIF, WebP</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="image-preview-container" style="display: none;">
                            <label class="form-label">{{ __('forums.image_preview') }}</label>
                            <div id="image-preview" class="row g-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="video" class="form-label">{{ __('forums.video_attachment') }}</label>
                            <input type="file" class="form-control @error('video') is-invalid @enderror" id="video" name="video" accept="video/*" data-max-size="52428800">
                            <div class="form-text">{{ __('forums.video_help_text') }} (Max: 50MB)</div>
                            <div class="form-text text-info">Supported formats: MP4, AVI, MOV, WMV, FLV, WebM, MKV</div>
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="video-preview" style="display: none;">
                            <label class="form-label">{{ __('forums.video_preview') }}</label>
                            <video id="preview-video" controls class="w-100" style="max-height: 300px;" preload="metadata">
                                <source id="video-source" src="" type="">
                                {{ __('forums.video_not_supported') }}
                            </video>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('forums.index') }}" class="btn btn-outline-secondary">{{ __('forums.cancel') }}</a>
                            <button type="submit" class="btn btn-success">{{ __('forums.create_topic_btn') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview handling
    const imagesInput = document.getElementById('images');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');

    imagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const maxSize = parseInt(imagesInput.dataset.maxSize); // 10MB = 10485760 bytes

        // Clear previous previews
        imagePreview.innerHTML = '';

        if (files.length > 0) {
            let validFiles = [];

            files.forEach((file, index) => {
                // Check file size
                if (file.size > maxSize) {
                    alert('Image ' + (index + 1) + ' size must be less than 10MB. Current size: ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB');
                    return;
                }

                // Check if it's an image
                if (file.type.startsWith('image/')) {
                    validFiles.push(file);
                    const url = URL.createObjectURL(file);

                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6';

                    const card = document.createElement('div');
                    card.className = 'card';

                    const img = document.createElement('img');
                    img.src = url;
                    img.className = 'card-img-top';
                    img.style.maxHeight = '200px';
                    img.style.objectFit = 'cover';

                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-2';
                    cardBody.innerHTML = '<small class="text-muted">' + file.name + '</small>';

                    card.appendChild(img);
                    card.appendChild(cardBody);
                    col.appendChild(card);
                    imagePreview.appendChild(col);
                } else {
                    alert('File ' + (index + 1) + ' is not a valid image file.');
                }
            });

            if (validFiles.length > 0) {
                imagePreviewContainer.style.display = 'block';
            } else {
                imagePreviewContainer.style.display = 'none';
            }
        } else {
            imagePreviewContainer.style.display = 'none';
        }
    });

    // Video preview handling
    const videoInput = document.getElementById('video');
    const videoPreview = document.getElementById('video-preview');
    const previewVideo = document.getElementById('preview-video');
    const videoSource = document.getElementById('video-source');

    videoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            // Check file size (50MB = 52428800 bytes)
            const maxSize = parseInt(videoInput.dataset.maxSize);

            if (file.size > maxSize) {
                alert('File size must be less than 50MB. Current size: ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB');
                videoInput.value = '';
                videoPreview.style.display = 'none';
                return;
            }

            if (file.type.startsWith('video/')) {
                const url = URL.createObjectURL(file);
                videoSource.src = url;
                videoSource.type = file.type; // Set proper MIME type
                previewVideo.load();
                videoPreview.style.display = 'block';
            } else {
                alert('{{ __("forums.please_select_video") }}');
                videoInput.value = '';
                videoPreview.style.display = 'none';
            }
        } else {
            videoPreview.style.display = 'none';
        }
    });

    // Harvest calendar fields toggle
    const harvestCheckbox = document.getElementById('is_harvest_post');
    const harvestFields = document.getElementById('harvest-fields');

    function toggleHarvestFields() {
        if (harvestCheckbox.checked) {
            harvestFields.style.display = 'block';
            // Make harvest fields required
            document.getElementById('product_name').required = true;
            document.getElementById('harvest_start_date').required = true;
            document.getElementById('harvest_end_date').required = true;
        } else {
            harvestFields.style.display = 'none';
            // Remove required attribute
            document.getElementById('product_name').required = false;
            document.getElementById('harvest_start_date').required = false;
            document.getElementById('harvest_end_date').required = false;
        }
    }

    // Initialize on page load
    toggleHarvestFields();

    // Toggle on checkbox change
    harvestCheckbox.addEventListener('change', toggleHarvestFields);
});
</script>
@endpush
@endsection