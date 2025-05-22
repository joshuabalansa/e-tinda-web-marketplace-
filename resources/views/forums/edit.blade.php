@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">Edit Topic</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('forums.update', $forum->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $forum->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select a category</option>
                                <option value="Crop Farming" {{ old('category', $forum->category) == 'Crop Farming' ? 'selected' : '' }}>Crop Farming</option>
                                <option value="Livestock" {{ old('category', $forum->category) == 'Livestock' ? 'selected' : '' }}>Livestock</option>
                                <option value="Organic Farming" {{ old('category', $forum->category) == 'Organic Farming' ? 'selected' : '' }}>Organic Farming</option>
                                <option value="Market Prices" {{ old('category', $forum->category) == 'Market Prices' ? 'selected' : '' }}>Market Prices</option>
                                <option value="Equipment" {{ old('category', $forum->category) == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content', $forum->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('forums.topic', $forum->id) }}" class="btn btn-outline-secondary">Cancel</a>
                            <div>
                                <form action="{{ route('forums.destroy', $forum->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this topic?')">Delete</button>
                                </form>
                                <button type="submit" class="btn btn-success">Update Topic</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection