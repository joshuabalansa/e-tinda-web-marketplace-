@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="fas fa-lock text-success" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 mb-4 text-success">Access Denied</h1>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <p class="lead mb-4">{{ trans('errors.403') }}</p>
                    <p class="text-muted mb-4">You don't have permission to access this page.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/" class="btn btn-success">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-success">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </button>
                    </div>
                </div>
            </div>
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                If you believe this is a mistake, please contact support.
            </p>
        </div>
    </div>
</div>
@endsection
