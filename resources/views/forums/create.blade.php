@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">Create New Topic</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('forums.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select a category</option>
                                <option value="Crop Farming" {{ old('category') == 'Crop Farming' ? 'selected' : '' }}>Crop Farming</option>
                                <option value="Livestock" {{ old('category') == 'Livestock' ? 'selected' : '' }}>Livestock</option>
                                <option value="Organic Farming" {{ old('category') == 'Organic Farming' ? 'selected' : '' }}>Organic Farming</option>
                                <option value="Market Prices" {{ old('category') == 'Market Prices' ? 'selected' : '' }}>Market Prices</option>
                                <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('forums.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Create Topic</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection