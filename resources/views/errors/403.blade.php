@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="fas fa-ban text-danger" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 mb-4">Access Denied</h1>
            <p class="lead mb-4">{{ trans('errors.403') }}</p>
            <div class="mb-4">
                <p>You don't have permission to access this page.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Go Home
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Go Back
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
