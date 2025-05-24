@extends('layouts.shop')

@section('content')
<!-- Forums Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="display-4 text-success fw-bold">Farmer Forums</h1>
        <p class="lead">Connect with other farmers, share knowledge, and ask questions</p>
    </div>
</div>

<!-- Forums Content Section -->
<div class="container my-5">
    <div class="row">
        <!-- Forum Categories -->
        <div class="col-lg-3 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Categories</h5>
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
                <h2 class="h4 text-success">Recent Discussions</h2>
                @auth
                <a href="{{ route('forums.create') }}" class="btn btn-success">New Topic</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-success">Login to Post</a>
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
                            <h5><a href="{{ route('forums.topic', $forum->id) }}" class="text-decoration-none text-dark">{{ $forum->title }}</a></h5>
                            <div class="text-muted small">
                                <span class="fw-bold">Category:</span> {{ $forum->category }}
                                <span class="mx-2">|</span>
                                <span class="fw-bold">Started by:</span> {{ $forum->user->name }}
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end mt-2 mt-md-0">
                            <div class="small text-muted">{{ $forum->created_at->diffForHumans() }}</div>
                            <div><span class="badge bg-success">{{ $forum->replies->count() }} replies</span></div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <h5 class="text-muted">No topics found</h5>
                    <p>Be the first to start a discussion!</p>
                    @auth
                    <a href="{{ route('forums.create') }}" class="btn btn-success">Create Topic</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-success">Login to Post</a>
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