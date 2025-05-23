@extends('layouts.shop')

@section('content')
<div class="container py-5">
    <h1 class="section-title text-center mb-5">Browse Categories</h1>

    <div class="row g-4">
        @foreach($categories as $category)
        <div class="col-md-4">
            <div class="card category-card h-100">
                <img src="{{ $category['image'] }}" class="card-img-top" alt="{{ $category['name'] }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $category['name'] }}</h5>
                    <p class="card-text">{{ $category['description'] }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success">{{ $category['product_count'] }} Products</span>
                        <a href="#" class="btn btn-outline-success">View Products</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection