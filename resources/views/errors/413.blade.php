@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        File Upload Too Large
                    </h2>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-file-video text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-danger mb-3">Video File Too Large</h3>
                    <p class="lead mb-4">
                        The video file you're trying to upload exceeds the maximum allowed size of <strong>50MB</strong>.
                    </p>

                    <div class="alert alert-info">
                        <h5 class="alert-heading">What you can do:</h5>
                        <ul class="mb-0 text-start">
                            <li>Compress your video to reduce file size</li>
                            <li>Use a video compression tool</li>
                            <li>Consider uploading a shorter video</li>
                            <li>Check if your video can be converted to a more efficient format</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Go Back
                        </a>
                        <a href="{{ route('forums.index') }}" class="btn btn-success">
                            <i class="fas fa-home me-1"></i> Back to Forums
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



