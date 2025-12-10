@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="fas fa-tools text-info" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 mb-4">Service Unavailable</h1>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <p class="lead mb-4">
                        @if(isset($message))
                            {{ $message }}
                        @else
                            We're temporarily unable to process your request.
                        @endif
                    </p>
                    <p class="text-muted mb-4">
                        Our service is temporarily unavailable. Please try again in a few moments.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/" class="btn btn-success">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-success">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </button>
                    </div>
                </div>
            </div>
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                We're working to restore service as quickly as possible.
            </p>
        </div>
    </div>
</div>
@endsection

