@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-title">Forum Moderation</h1>
                    <p class="page-description">Manage forum topics and replies</p>
                </div>
                <div class="col-md-6 text-right">
                    <!-- Manage Replies button - feature coming soon -->
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-body text-center">
                        <h3 class="text-primary">{{ $stats['total_forums'] }}</h3>
                        <p>Total Forums</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-success">
                    <div class="panel-body text-center">
                        <h3 class="text-success">{{ $stats['active_forums'] }}</h3>
                        <p>Active</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-warning">
                    <div class="panel-body text-center">
                        <h3 class="text-warning">{{ $stats['pending_forums'] }}</h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-danger">
                    <div class="panel-body text-center">
                        <h3 class="text-danger">{{ $stats['flagged_forums'] }}</h3>
                        <p>Flagged</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <h3 class="text-info">{{ $stats['hidden_forums'] }}</h3>
                        <p>Hidden</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h3 class="text-muted">{{ $stats['total_replies'] }}</h3>
                        <p>Total Replies</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Filters & Search</h3>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('admin.forums.index') }}" class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="status" class="control-label">Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="flagged" class="control-label">Flagged:</label>
                                <select name="flagged" id="flagged" class="form-control">
                                    <option value="">All</option>
                                    <option value="1" {{ request('flagged') == '1' ? 'selected' : '' }}>Flagged Only</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8">
                            <div class="form-group">
                                <label for="search" class="control-label">Search:</label>
                                <input type="text" name="search" id="search" class="form-control"
                                       placeholder="Search forums..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('admin.forums.index') }}" class="btn btn-default">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Bulk Actions</h3>
            </div>
            <div class="panel-body">
                <form id="bulk-form" method="POST" action="{{ route('admin.forums.bulk-action') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="bulk-action" class="control-label">Action:</label>
                                <select name="action" id="bulk-action" class="form-control" required>
                                    <option value="">Select Action</option>
                                    <option value="approve">Approve Selected</option>
                                    <option value="hide">Hide Selected</option>
                                    <option value="delete">Delete Selected</option>
                                    <option value="flag">Flag Selected</option>
                                    <option value="unflag">Unflag Selected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?')">
                                        Apply to Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label class="control-label">Quick Stats:</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Total: {{ $stats['total_forums'] }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-success">Active: {{ $stats['active_forums'] }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-warning">Pending: {{ $stats['pending_forums'] }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-danger">Flagged: {{ $stats['flagged_forums'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Forums Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Forum Topics</h3>
            </div>
            <div class="panel-body">
                @if($forums->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Flagged</th>
                                    <th>Replies</th>
                                    <th>Created</th>
                                    <th>Moderated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($forums as $forum)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="forum_ids[]" value="{{ $forum->id }}" class="forum-checkbox">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.forums.show', $forum) }}" class="text-primary">
                                            {{ Str::limit($forum->title, 50) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $forum->user) }}" class="text-info">
                                            {{ $forum->user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="label label-info">{{ $forum->category }}</span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @if($forum->is_flagged)
                                            <span class="label label-danger">Flagged</span>
                                        @else
                                            <span class="label label-success">Clean</span>
                                        @endif
                                    </td>
                                    <td>{{ $forum->replies->count() }}</td>
                                    <td>{{ $forum->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($forum->moderated_at)
                                            <small>{{ $forum->moderated_at->format('M d, Y') }}</small>
                                            @if($forum->moderator)
                                                <br><small class="text-muted">by {{ $forum->moderator->name }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Not moderated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.forums.show', $forum) }}" class="btn btn-xs btn-info">
                                                <i class="entypo-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.forums.toggle-flag', $forum) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-xs {{ $forum->is_flagged ? 'btn-success' : 'btn-warning' }}">
                                                    <i class="entypo-{{ $forum->is_flagged ? 'check' : 'flag' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.forums.destroy', $forum) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to permanently delete this forum?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="text-center">
                        {{ $forums->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center text-muted">
                        <h4>No forums found</h4>
                        <p>Try adjusting your filters or search criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const forumCheckboxes = document.querySelectorAll('.forum-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        forumCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update select all checkbox when individual checkboxes change
    forumCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.forum-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === forumCheckboxes.length;
        });
    });

    // Bulk form submission
    document.getElementById('bulk-form').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.forum-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one forum.');
            return false;
        }

        // Add selected forum IDs to form
        checkedBoxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'forum_ids[]';
            input.value = checkbox.value;
            this.appendChild(input);
        });
    });
});
</script>
@endsection
