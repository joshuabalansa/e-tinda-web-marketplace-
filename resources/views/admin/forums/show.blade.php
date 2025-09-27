@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-title">Forum Moderation</h1>
                    <p class="page-description">Moderate forum topic: {{ $forum->title }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('admin.forums.index') }}" class="btn btn-default">
                        <i class="entypo-left"></i> Back to Forums
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-body text-center">
                        <h3 class="text-primary">{{ $stats['total_replies'] }}</h3>
                        <p>Total Replies</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-success">
                    <div class="panel-body text-center">
                        <h3 class="text-success">{{ $stats['active_replies'] }}</h3>
                        <p>Active Replies</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-body text-center">
                        <h3 class="text-danger">{{ $stats['flagged_replies'] }}</h3>
                        <p>Flagged Replies</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <h3 class="text-info">{{ $stats['helpful_votes'] }}</h3>
                        <p>Helpful Votes</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Forum Details -->
            <div class="col-lg-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Forum Topic Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <h4>{{ $forum->title }}</h4>
                                <p class="text-muted">by <strong>{{ $forum->user->name }}</strong> on {{ $forum->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="col-lg-4 text-right">
                                <div class="btn-group">
                                    @switch($forum->status)
                                        @case('active')
                                            <span class="label label-success">Active</span>
                                            @break
                                        @case('pending')
                                            <span class="label label-warning">Pending</span>
                                            @break
                                        @case('hidden')
                                            <span class="label label-danger">Hidden</span>
                                            @break
                                        @case('deleted')
                                            <span class="label label-default">Deleted</span>
                                            @break
                                    @endswitch

                                    @if($forum->is_flagged)
                                        <span class="label label-danger">Flagged</span>
                                    @else
                                        <span class="label label-success">Clean</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="forum-content">
                            <h5>Content:</h5>
                            <div class="well">
                                {!! nl2br(e($forum->content)) !!}
                            </div>

                            @if($forum->hasVideo())
                                <h5>Video Attachment:</h5>
                                <div class="well">
                                    <video controls style="max-width: 100%; height: auto;">
                                        <source src="{{ $forum->video_url }}" type="{{ $forum->video_mime_type }}">
                                        Your browser does not support the video tag.
                                    </video>
                                    <p class="text-muted">
                                        <small>File: {{ $forum->video_original_name }} ({{ $forum->video_file_size }})</small>
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if($forum->moderation_notes)
                            <div class="alert alert-info">
                                <h5>Moderation Notes:</h5>
                                <p>{{ $forum->moderation_notes }}</p>
                                @if($forum->moderated_at)
                                    <small class="text-muted">
                                        Moderated on {{ $forum->moderated_at->format('M d, Y H:i') }}
                                        @if($forum->moderator)
                                            by {{ $forum->moderator->name }}
                                        @endif
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Replies -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Replies ({{ $forum->replies->count() }})</h3>
                    </div>
                    <div class="panel-body">
                        @if($forum->replies->count() > 0)
                            @foreach($forum->replies as $reply)
                                <div class="reply-item {{ $reply->is_flagged ? 'alert alert-danger' : '' }}" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6>
                                                <strong>{{ $reply->user->name }}</strong>
                                                <small class="text-muted">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                                            </h6>
                                            <p>{{ $reply->content }}</p>

                                            @if($reply->hasVideo())
                                                <div class="reply-video">
                                                    <video controls style="max-width: 300px; height: auto;">
                                                        <source src="{{ $reply->video_url }}" type="{{ $reply->video_mime_type }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="btn-group-vertical">
                                                @switch($reply->status)
                                                    @case('active')
                                                        <span class="label label-success">Active</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="label label-warning">Pending</span>
                                                        @break
                                                    @case('hidden')
                                                        <span class="label label-danger">Hidden</span>
                                                        @break
                                                    @case('deleted')
                                                        <span class="label label-default">Deleted</span>
                                                        @break
                                                @endswitch

                                                @if($reply->is_flagged)
                                                    <span class="label label-danger">Flagged</span>
                                                @endif

                                                <div class="btn-group" style="margin-top: 10px;">
                                                    <form method="POST" action="{{ route('admin.forums.replies.toggle-flag', $reply) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs {{ $reply->is_flagged ? 'btn-success' : 'btn-warning' }}">
                                                            <i class="entypo-{{ $reply->is_flagged ? 'check' : 'flag' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.forums.replies.destroy', $reply) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger">
                                                            <i class="entypo-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <p>No replies yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Moderation Actions -->
            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Moderation Actions</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Status Update -->
                        <form method="POST" action="{{ route('admin.forums.update-status', $forum) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="status">Update Status:</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" {{ $forum->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ $forum->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="hidden" {{ $forum->status == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                    <option value="deleted" {{ $forum->status == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="moderation_notes">Moderation Notes:</label>
                                <textarea name="moderation_notes" id="moderation_notes" class="form-control" rows="3" placeholder="Add notes about this moderation action...">{{ $forum->moderation_notes }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                        </form>

                        <hr>

                        <!-- Flag Toggle -->
                        <form method="POST" action="{{ route('admin.forums.toggle-flag', $forum) }}">
                            @csrf
                            <div class="form-group">
                                <label>Flag Status:</label>
                                <p class="form-control-static">
                                    @if($forum->is_flagged)
                                        <span class="label label-danger">Currently Flagged</span>
                                    @else
                                        <span class="label label-success">Not Flagged</span>
                                    @endif
                                </p>
                            </div>
                            <button type="submit" class="btn btn-{{ $forum->is_flagged ? 'success' : 'warning' }} btn-block">
                                {{ $forum->is_flagged ? 'Unflag' : 'Flag' }} Forum
                            </button>
                        </form>

                        <hr>

                        <!-- Delete Forum -->
                        <form method="POST" action="{{ route('admin.forums.destroy', $forum) }}" onsubmit="return confirm('Are you sure you want to permanently delete this forum? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="entypo-trash"></i> Delete Forum Permanently
                            </button>
                        </form>

                        <hr>

                        <!-- Forum Info -->
                        <div class="well">
                            <h5>Forum Information</h5>
                            <p><strong>Category:</strong> {{ $forum->category }}</p>
                            <p><strong>Views:</strong> {{ $forum->views }}</p>
                            <p><strong>Created:</strong> {{ $forum->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Last Updated:</strong> {{ $forum->updated_at->format('M d, Y H:i') }}</p>
                            @if($forum->moderated_at)
                                <p><strong>Last Moderated:</strong> {{ $forum->moderated_at->format('M d, Y H:i') }}</p>
                                @if($forum->moderator)
                                    <p><strong>Moderated By:</strong> {{ $forum->moderator->name }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
