@extends('layouts.shop')

@section('content')
<!-- Forums Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="display-4 text-success fw-bold">{{ __('forums.farmer_forums') }}</h1>
        <p class="lead">{{ __('forums.connect_description') }}</p>
    </div>
</div>

<!-- Forums Content Section -->
<div class="container my-5">
    <div class="row">
        <!-- Forum Categories -->
        <div class="col-lg-3 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">{{ __('forums.categories') }}</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($categories as $category)
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        {{ $category }}
                        <span class="badge bg-success rounded-pill">
                            {{ \App\Models\Forum::where('category', $category)->count() }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Forum Topics -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 text-success">{{ __('forums.recent_discussions') }}</h2>
                @auth
                <a href="{{ route('forums.create') }}" class="btn btn-success">{{ __('forums.new_topic') }}</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-success">{{ __('forums.login_to_post') }}</a>
                @endauth
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Topics List -->
            @forelse($forums as $forum)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5>
                                <a href="{{ route('forums.topic', $forum->id) }}" class="text-decoration-none text-dark">
                                    {{ $forum->title }}
                                    @if($forum->hasVideo())
                                        <i class="fas fa-video text-success ms-2" title="{{ __('forums.has_video') }}"></i>
                                    @endif
                                </a>
                            </h5>
                            <div class="text-muted small">
                                <span class="fw-bold">{{ __('forums.category') }}:</span> {{ $forum->category }}
                                <span class="mx-2">|</span>
                                <span class="fw-bold">{{ __('forums.started_by') }}:</span> {{ $forum->user->name }}
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end mt-2 mt-md-0">
                            <div class="small text-muted">{{ $forum->created_at->diffForHumans() }}</div>
                            <div><span class="badge bg-success">{{ $forum->replies->count() }} {{ __('forums.replies') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <h5 class="text-muted">{{ __('forums.no_topics_found') }}</h5>
                    <p>{{ __('forums.be_first_discussion') }}</p>
                    @auth
                    <a href="{{ route('forums.create') }}" class="btn btn-success">{{ __('forums.create_topic') }}</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-success">{{ __('forums.login_to_post') }}</a>
                    @endauth
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $forums->links() }}
            </div>
        </div>
    </div>
</div>
@endsection