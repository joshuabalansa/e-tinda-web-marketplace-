@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">{{ __('forums.edit_topic') }}</h2>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('forums.update', $forum->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Hidden fields to preserve existing video data -->
                        @if($forum->hasVideo())
                        <input type="hidden" name="existing_video_path" value="{{ $forum->video_path }}">
                        <input type="hidden" name="existing_video_name" value="{{ $forum->video_original_name }}">
                        @else
                        <input type="hidden" name="existing_video_path" value="">
                        <input type="hidden" name="existing_video_name" value="">
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('forums.title') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $forum->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">{{ __('forums.category') }}</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">{{ __('forums.select_category') }}</option>
                                <option value="Crop Farming" {{ old('category', $forum->category) == 'Crop Farming' ? 'selected' : '' }}>{{ __('forums.crop_farming') }}</option>
                                <option value="Livestock" {{ old('category', $forum->category) == 'Livestock' ? 'selected' : '' }}>{{ __('forums.livestock') }}</option>
                                <option value="Organic Farming" {{ old('category', $forum->category) == 'Organic Farming' ? 'selected' : '' }}>{{ __('forums.organic_farming') }}</option>
                                <option value="Market Prices" {{ old('category', $forum->category) == 'Market Prices' ? 'selected' : '' }}>{{ __('forums.market_prices') }}</option>
                                <option value="Equipment" {{ old('category', $forum->category) == 'Equipment' ? 'selected' : '' }}>{{ __('forums.equipment') }}</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">{{ __('forums.content') }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content', $forum->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Video Display -->
                        @if($forum->hasVideo())
                        <div class="mb-3">
                            <label class="form-label">{{ __('forums.current_video') }}</label>
                            <div class="border rounded p-3">
                                <video controls class="w-100" style="max-height: 300px;" preload="metadata"
                                       onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <source src="{{ $forum->video_url }}" type="{{ $forum->video_mime_type }}">
                                    {{ __('forums.video_not_supported') }}
                                </video>
                                <div class="alert alert-warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ __('forums.video_load_error') }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">{{ $forum->video_original_name }}</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- New Video Upload -->
                        <div class="mb-3">
                            <label for="video" class="form-label">{{ __('forums.video_attachment') }}</label>
                            <input type="file" class="form-control @error('video') is-invalid @enderror" id="video" name="video" accept="video/*" data-max-size="52428800">
                            <div class="form-text">{{ __('forums.video_help_text') }} (Max: 50MB)</div>
                            <div class="form-text text-info">Supported formats: MP4, AVI, MOV, WMV, FLV, WebM, MKV</div>
                            @if($forum->hasVideo())
                                <div class="form-text text-warning">{{ __('forums.video_replace_warning') }}</div>
                            @endif
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="video-preview" style="display: none;">
                            <label class="form-label">{{ __('forums.video_preview') }}</label>
                            <video id="preview-video" controls class="w-100" style="max-height: 300px;">
                                <source id="video-source" src="" type="video/mp4">
                                {{ __('forums.video_not_supported') }}
                            </video>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('forums.topic', $forum->id) }}" class="btn btn-outline-secondary">{{ __('forums.cancel') }}</a>
                            <div>
                                <button type="submit" class="btn btn-success me-2">{{ __('forums.update_topic') }}</button>
                                <button type="button" class="btn btn-danger" onclick="deleteForum()">{{ __('forums.delete') }}</button>
                            </div>
                        </div>
                    </form>

                    <!-- Separate delete form -->
                    <form id="delete-form" action="{{ route('forums.destroy', $forum->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoInput = document.getElementById('video');
    const videoPreview = document.getElementById('video-preview');
    const previewVideo = document.getElementById('preview-video');
    const videoSource = document.getElementById('video-source');
    const form = document.querySelector('form');

    // Form submission handler to ensure video data is preserved
    form.addEventListener('submit', function(e) {
        const existingVideoPath = document.querySelector('input[name="existing_video_path"]').value;
        const existingVideoName = document.querySelector('input[name="existing_video_name"]').value;
        const newVideoFile = videoInput.files[0];

        // Ensure hidden fields are set
        if (existingVideoPath && existingVideoName) {
            document.querySelector('input[name="existing_video_path"]').value = existingVideoPath;
            document.querySelector('input[name="existing_video_name"]').value = existingVideoName;
        }

        // Prevent form submission if there are validation errors
        const requiredFields = form.querySelectorAll('[required]');
        let hasErrors = false;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                hasErrors = true;
            }
        });

        if (hasErrors) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });

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
});

function deleteForum() {
    if (confirm('{{ __('forums.delete_confirmation') }}')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
@endsection