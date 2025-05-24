@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="panel-title">Product Details</h3>
                    <div>
                        <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-warning">
                            <i class="entypo-pencil"></i> Edit
                        </a>
                        <a href="{{ route('farmer.products.index') }}" class="btn btn-secondary">
                            <i class="entypo-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($product->image_url)
                            <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="img-responsive">
                        @else
                            <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-white">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $product->name }}</h4>
                        <p class="text-muted">Category: {{ $product->category }}</p>

                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <p>{{ $product->description }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Price Per Unit</label>
                                    <p class="h4 text-primary">${{ number_format($product->price_per_unit, 2) }} / {{ $product->unit_type }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Stock Quantity</label>
                                    <p class="h4">{{ $product->stock_quantity }} {{ $product->unit_type }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Harvest Date</label>
                            <p>{{ \Carbon\Carbon::parse($product->harvest_date)->format('F j, Y') }}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <p>
                                <span class="badge {{ $product->status === 'available' ? 'bg-success' : ($product->status === 'out_of_stock' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Created</label>
                            <p>{{ $product->created_at->format('F j, Y') }}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Last Updated</label>
                            <p>{{ $product->updated_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection