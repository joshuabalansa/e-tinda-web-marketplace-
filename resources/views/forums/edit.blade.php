@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">{{ __('forums.edit_topic') }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('forums.update', $forum->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('forums.title') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $forum->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">{{ __('forums.category') }}</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">{{ __('forums.select_category') }}</option>
                                <option value="Crop Farming" {{ old('category', $forum->category) == 'Crop Farming' ? 'selected' : '' }}>{{ __('forums.crop_farming') }}</option>
                                <option value="Livestock" {{ old('category', $forum->category) == 'Livestock' ? 'selected' : '' }}>{{ __('forums.livestock') }}</option>
                                <option value="Organic Farming" {{ old('category', $forum->category) == 'Organic Farming' ? 'selected' : '' }}>{{ __('forums.organic_farming') }}</option>
                                <option value="Market Prices" {{ old('category', $forum->category) == 'Market Prices' ? 'selected' : '' }}>{{ __('forums.market_prices') }}</option>
                                <option value="Equipment" {{ old('category', $forum->category) == 'Equipment' ? 'selected' : '' }}>{{ __('forums.equipment') }}</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">{{ __('forums.content') }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content', $forum->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('forums.topic', $forum->id) }}" class="btn btn-outline-secondary">{{ __('forums.cancel') }}</a>
                            <div>
                                <form action="{{ route('forums.destroy', $forum->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger me-2" onclick="return confirm('{{ __('forums.delete_confirmation') }}')">{{ __('forums.delete') }}</button>
                                </form>
                                <button type="submit" class="btn btn-success">{{ __('forums.update_topic') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection