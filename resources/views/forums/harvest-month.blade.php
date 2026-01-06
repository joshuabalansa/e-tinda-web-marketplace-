@extends('layouts.shop')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/harvest-calendar.css') }}">
@endpush

@section('content')
<!-- Harvest Month Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('forums.index') }}" class="text-success">{{ __('forums.breadcrumb_forums') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('forums.harvest-calendar') }}" class="text-success">{{ __('forums.harvest_calendar') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if($month == 1) {{ __('forums.month_january') }}
                    @elseif($month == 2) {{ __('forums.month_february') }}
                    @elseif($month == 3) {{ __('forums.month_march') }}
                    @elseif($month == 4) {{ __('forums.month_april') }}
                    @elseif($month == 5) {{ __('forums.month_may') }}
                    @elseif($month == 6) {{ __('forums.month_june') }}
                    @elseif($month == 7) {{ __('forums.month_july') }}
                    @elseif($month == 8) {{ __('forums.month_august') }}
                    @elseif($month == 9) {{ __('forums.month_september') }}
                    @elseif($month == 10) {{ __('forums.month_october') }}
                    @elseif($month == 11) {{ __('forums.month_november') }}
                    @else {{ __('forums.month_december') }}
                    @endif
                </li>
            </ol>
        </nav>
        <h1 class="display-4 text-success fw-bold">
            @if($month == 1) {{ __('forums.month_january') }}
            @elseif($month == 2) {{ __('forums.month_february') }}
            @elseif($month == 3) {{ __('forums.month_march') }}
            @elseif($month == 4) {{ __('forums.month_april') }}
            @elseif($month == 5) {{ __('forums.month_may') }}
            @elseif($month == 6) {{ __('forums.month_june') }}
            @elseif($month == 7) {{ __('forums.month_july') }}
            @elseif($month == 8) {{ __('forums.month_august') }}
            @elseif($month == 9) {{ __('forums.month_september') }}
            @elseif($month == 10) {{ __('forums.month_october') }}
            @elseif($month == 11) {{ __('forums.month_november') }}
            @else {{ __('forums.month_december') }}
            @endif - {{ __('forums.harvest_posts') }}
        </h1>
        <p class="lead">{{ __('forums.harvest_calendar_subtitle') }}</p>
    </div>
</div>

<!-- Harvest Month Content Section -->
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('forums.harvest-calendar') }}" class="btn btn-outline-success">
                <i class="fas fa-arrow-left me-2"></i>{{ __('forums.back') }} {{ __('forums.harvest_calendar') }}
            </a>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group" role="group">
                @if($month > 1)
                    <a href="{{ route('forums.harvest-calendar.month', $month - 1) }}" class="btn btn-outline-success">
                        <i class="fas fa-chevron-left me-2"></i>
                        @if($month - 1 == 1) {{ __('forums.month_january') }}
                        @elseif($month - 1 == 2) {{ __('forums.month_february') }}
                        @elseif($month - 1 == 3) {{ __('forums.month_march') }}
                        @elseif($month - 1 == 4) {{ __('forums.month_april') }}
                        @elseif($month - 1 == 5) {{ __('forums.month_may') }}
                        @elseif($month - 1 == 6) {{ __('forums.month_june') }}
                        @elseif($month - 1 == 7) {{ __('forums.month_july') }}
                        @elseif($month - 1 == 8) {{ __('forums.month_august') }}
                        @elseif($month - 1 == 9) {{ __('forums.month_september') }}
                        @elseif($month - 1 == 10) {{ __('forums.month_october') }}
                        @elseif($month - 1 == 11) {{ __('forums.month_november') }}
                        @else {{ __('forums.month_december') }}
                        @endif
                    </a>
                @endif
                @if($month < 12)
                    <a href="{{ route('forums.harvest-calendar.month', $month + 1) }}" class="btn btn-outline-success">
                        @if($month + 1 == 1) {{ __('forums.month_january') }}
                        @elseif($month + 1 == 2) {{ __('forums.month_february') }}
                        @elseif($month + 1 == 3) {{ __('forums.month_march') }}
                        @elseif($month + 1 == 4) {{ __('forums.month_april') }}
                        @elseif($month + 1 == 5) {{ __('forums.month_may') }}
                        @elseif($month + 1 == 6) {{ __('forums.month_june') }}
                        @elseif($month + 1 == 7) {{ __('forums.month_july') }}
                        @elseif($month + 1 == 8) {{ __('forums.month_august') }}
                        @elseif($month + 1 == 9) {{ __('forums.month_september') }}
                        @elseif($month + 1 == 10) {{ __('forums.month_october') }}
                        @elseif($month + 1 == 11) {{ __('forums.month_november') }}
                        @else {{ __('forums.month_december') }}
                        @endif
                        <i class="fas fa-chevron-right ms-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($harvestPosts->count() > 0)
        @php
            $groupedByCategory = $harvestPosts->groupBy('product_category');
        @endphp

        @foreach($groupedByCategory as $category => $posts)
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-seedling me-2"></i>{{ $category ?: __('forums.category_other') }}
                        <span class="badge bg-light text-success ms-2">{{ $posts->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($posts as $post)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-success border-opacity-25 h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('forums.topic', $post->id) }}" class="text-decoration-none text-dark">
                                                {{ $post->product_name }}
                                            </a>
                                        </h6>
                                        <p class="card-text small text-muted mb-2">
                                            {{ $post->title }}
                                        </p>
                                        <div class="small text-muted mb-2">
                                            @if($post->harvest_start_date && $post->harvest_end_date)
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $post->harvest_start_date->format('M d') }} - {{ $post->harvest_end_date->format('M d') }}
                                            @endif
                                            @if($post->harvest_season)
                                                <span class="badge bg-success ms-2">{{ $post->harvest_season }}</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $post->user->name }}
                                        </div>
                                        <a href="{{ route('forums.topic', $post->id) }}" class="btn btn-sm btn-outline-success mt-2">
                                            {{ __('forums.view_product_posts') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('forums.no_harvest_posts') }}</h5>
                <p class="text-muted">{{ __('forums.harvest_calendar_description') }}</p>
                <a href="{{ route('forums.harvest-calendar') }}" class="btn btn-success">
                    {{ __('forums.view_full_calendar') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

