@extends('layouts.app')

@section('content')
<!-- Topic Header Section -->
<div class="container-fluid bg-light py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('forums.index') }}" class="text-success">{{ __('forums.breadcrumb_forums') }}</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-success">{{ $forum->category }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $forum->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Topic Content Section -->
<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
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

            <!-- Topic Header -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">{{ $forum->title }}</h2>
                    @auth
                        @if(Auth::id() === $forum->user_id)
                        <a href="{{ route('forums.edit', $forum->id) }}" class="btn btn-sm btn-light text-success">{{ __('forums.edit') }}</a>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            {{-- <img src="https://via.placeholder.com/50" class="rounded-circle me-2" alt="User Avatar"> --}}
                            <div>
                                <h5 class="mb-0">{{ $forum->user->name }}</h5>
                                <small class="text-muted">{{ __('forums.posted') }} {{ $forum->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-success">{{ $forum->category }}</span>
                        </div>
                    </div>
                    <div class="topic-content">
                        {!! nl2br(e($forum->content)) !!}
                    </div>
                </div>
            </div>

            <!-- Replies -->
            <h3 class="h5 mb-3">{{ $forum->replies->count() }} {{ __('forums.replies_count') }}</h3>

            @forelse($forum->replies as $reply)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        {{-- <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="User Avatar"> --}}
                        <div>
                            <h5 class="mb-0">{{ $reply->user->name }}</h5>
                            <small class="text-muted">{{ __('forums.posted') }} {{ $reply->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <p>{!! nl2br(e($reply->content)) !!}</p>
                    <div class="d-flex justify-content-end">
                        @auth
                        <form action="{{ route('forums.helpful', $reply->id) }}" method="POST" class="me-2">
                            @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-thumbs-up"></i> {{ __('forums.helpful') }} ({{ $reply->helpful_votes }})
                        </button>
                        </form>
                        @else
                        <button class="btn btn-sm btn-outline-secondary me-2" disabled>
                            <i class="fas fa-thumbs-up"></i> {{ __('forums.helpful') }} ({{ $reply->helpful_votes }})
                        </button>
                        @endauth
                        @auth
                        <button class="btn btn-sm btn-outline-secondary reply-button" data-reply-to="{{ $reply->user->name }}">{{ __('forums.reply') }}</button>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">{{ __('forums.login_to_reply') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="card mb-3">
                <div class="card-body text-center py-4">
                    <p class="text-muted mb-0">{{ __('forums.no_replies_yet') }}</p>
                </div>
            </div>
            @endforelse

            <!-- Reply Form -->
            @auth
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="h5 mb-0">{{ __('forums.post_reply') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('forums.reply', $forum->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="4" placeholder="{{ __('forums.write_reply_placeholder') }}" required></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">{{ __('forums.post_reply_btn') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-4">
                    <p class="mb-3">{{ __('forums.please_login_reply') }}</p>
                    <a href="{{ route('login') }}" class="btn btn-success">{{ __('forums.login') }}</a>
                </div>
            </div>
            @endauth
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Topics -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">{{ __('forums.related_topics') }}</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach(\App\Models\Forum::where('category', $forum->category)
                        ->where('id', '!=', $forum->id)
                        ->latest()
                        ->take(3)
                        ->get() as $relatedTopic)
                    <a href="{{ route('forums.topic', $relatedTopic->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $relatedTopic->title }}</h6>
                            <small class="text-muted">{{ $relatedTopic->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted">{{ $relatedTopic->replies->count() }} replies</small>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Forum Rules -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">{{ __('forums.forum_rules') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ __('forums.be_respectful') }}</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ __('forums.stay_on_topic') }}</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ __('forums.no_spam') }}</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> {{ __('forums.share_knowledge') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const replyButtons = document.querySelectorAll('.reply-button');
        const replyTextarea = document.querySelector('textarea[name="content"]');

        replyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const replyTo = this.dataset.replyTo;
                replyTextarea.value = `@${replyTo} `;
                replyTextarea.focus();
            });
        });
    });
</script>
@endpush
@endsection