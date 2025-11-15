@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-circle text-success" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 mb-4">Page Not Found</h1>
            <p class="lead mb-4">Oops! The page you're looking for doesn't seem to exist.</p>
            <div class="mb-4">
                <p>Here are some helpful links instead:</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/" class="btn btn-success">
                        <i class="fas fa-home me-2"></i>Go Home
                    </a>
                    <a href="/shop" class="btn btn-outline-success">
                        <i class="fas fa-shopping-basket me-2"></i>Visit Shop
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
