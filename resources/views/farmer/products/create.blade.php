@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add New Product</h3>
            </div>

            <div class="panel-body">
                @if ($errors->any())
                    <script>
                        $(document).ready(function() {
                            @foreach ($errors->all() as $error)
                                toastr.error("{{ $error }}");
                            @endforeach
                        });
                    </script>
                @endif
                <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="control-label">Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category" class="control-label">Category</label>
                        <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="Vegetables" {{ old('category') == 'Vegetables' ? 'selected' : '' }}>Vegetables</option>
                            <option value="Fruits" {{ old('category') == 'Fruits' ? 'selected' : '' }}>Fruits</option>
                            <option value="Grains" {{ old('category') == 'Grains' ? 'selected' : '' }}>Grains</option>
                            <option value="Dairy" {{ old('category') == 'Dairy' ? 'selected' : '' }}>Dairy</option>
                            <option value="Meat" {{ old('category') == 'Meat' ? 'selected' : '' }}>Meat</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price_per_unit" class="control-label">Price Per Unit</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="number" step="0.01" class="form-control @error('price_per_unit') is-invalid @enderror" id="price_per_unit" name="price_per_unit" value="{{ old('price_per_unit') }}" required>
                            @error('price_per_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="unit_type" class="control-label">Unit Type</label>
                        <select class="form-control @error('unit_type') is-invalid @enderror" id="unit_type" name="unit_type" required>
                            <option value="">Select unit type</option>
                            <option value="kg" {{ old('unit_type') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ old('unit_type') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="lb" {{ old('unit_type') == 'lb' ? 'selected' : '' }}>Pound (lb)</option>
                            <option value="piece" {{ old('unit_type') == 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="dozen" {{ old('unit_type') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                        </select>
                        @error('unit_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity" class="control-label">Stock Quantity</label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harvest_date" class="control-label">Harvest Date</label>
                        <input type="date" class="form-control @error('harvest_date') is-invalid @enderror" id="harvest_date" name="harvest_date" value="{{ old('harvest_date') }}" required>
                        @error('harvest_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image_url" class="control-label">Product Image</label>
                        <input type="file" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" accept="image/*">
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="control-label">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="entypo-plus"></i> Add Product
                        </button>
                        <a href="{{ route('farmer.products.index') }}" class="btn btn-secondary">
                            <i class="entypo-left"></i> Back to List
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection