@extends('layouts.dashboard')
@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Farmer Dashboard</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Farmer Overview</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Dashboard content will be implemented here -->
</div>

@endsection

@push('styles')
<style>
.page-header {
    background: #f8f9fa;
    padding: 20px 0;
    margin-bottom: 30px;
    border-radius: 8px;
}

.page-title {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 5px 0 0 0;
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #666;
}

.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-body {
    padding: 40px;
}
</style>
@endpush