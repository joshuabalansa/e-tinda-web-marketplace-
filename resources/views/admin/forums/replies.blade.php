@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-title">Forum Replies Moderation</h1>
                    <p class="page-description">Manage forum replies and comments</p>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('admin.forums.index') }}" class="btn btn-primary">
                        <i class="entypo-chat"></i> Manage Forums
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Filters & Search</h3>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('admin.forums.replies') }}" class="form-inline">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="flagged">Flagged:</label>
                        <select name="flagged" id="flagged" class="form-control">
                            <option value="">All</option>
                            <option value="1" {{ request('flagged') == '1' ? 'selected' : '' }}>Flagged Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search">Search:</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Search replies..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.forums.replies') }}" class="btn btn-default">Clear</a>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Bulk Actions</h3>
            </div>
            <div class="panel-body">
                <form id="bulk-form" method="POST" action="{{ route('admin.forums.replies.bulk-action') }}">
                    @csrf
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="bulk-action">Action:</label>
                            <select name="action" id="bulk-action" class="form-control" required>
                                <option value="">Select Action</option>
                                <option value="approve">Approve Selected</option>
                                <option value="hide">Hide Selected</option>
                                <option value="delete">Delete Selected</option>
                                <option value="flag">Flag Selected</option>
                                <option value="unflag">Unflag Selected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?')">
                            Apply to Selected
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Replies Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Forum Replies</h3>
            </div>
            <div class="panel-body">
                @if($replies->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Content</th>
                                    <th>Author</th>
                                    <th>Forum Topic</th>
                                    <th>Status</th>
                                    <th>Flagged</th>
                                    <th>Helpful Votes</th>
                                    <th>Created</th>
                                    <th>Moderated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($replies as $reply)
                                <tr class="{{ $reply->is_flagged ? 'danger' : '' }}">
                                    <td>
                                        <input type="checkbox" name="reply_ids[]" value="{{ $reply->id }}" class="reply-checkbox">
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            {{ Str::limit($reply->content, 100) }}
                                            @if($reply->hasVideo())
                                                <br><small class="text-info"><i class="entypo-video"></i> Has video</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $reply->user) }}" class="text-info">
                                            {{ $reply->user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.forums.show', $reply->forum) }}" class="text-primary">
                                            {{ Str::limit($reply->forum->title, 30) }}
                                        </a>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @if($reply->is_flagged)
                                            <span class="label label-danger">Flagged</span>
                                        @else
                                            <span class="label label-success">Clean</span>
                                        @endif
                                    </td>
                                    <td>{{ $reply->helpful_votes }}</td>
                                    <td>{{ $reply->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($reply->moderated_at)
                                            <small>{{ $reply->moderated_at->format('M d, Y') }}</small>
                                            @if($reply->moderator)
                                                <br><small class="text-muted">by {{ $reply->moderator->name }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Not moderated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-info" onclick="showReplyDetails({{ $reply->id }})">
                                                <i class="entypo-eye"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.forums.replies.toggle-flag', $reply) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-xs {{ $reply->is_flagged ? 'btn-success' : 'btn-warning' }}">
                                                    <i class="entypo-{{ $reply->is_flagged ? 'check' : 'flag' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.forums.replies.destroy', $reply) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to permanently delete this reply?')">
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
                        {{ $replies->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center text-muted">
                        <h4>No replies found</h4>
                        <p>Try adjusting your filters or search criteria.</p>
                    </div>
                @endif
            </div>
        </div>
</div>

<!-- Reply Details Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reply Details</h4>
            </div>
            <div class="modal-body" id="replyModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const replyCheckboxes = document.querySelectorAll('.reply-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        replyCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update select all checkbox when individual checkboxes change
    replyCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.reply-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === replyCheckboxes.length;
        });
    });

    // Bulk form submission
    document.getElementById('bulk-form').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.reply-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one reply.');
            return false;
        }

        // Add selected reply IDs to form
        checkedBoxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'reply_ids[]';
            input.value = checkbox.value;
            this.appendChild(input);
        });
    });
});

function showReplyDetails(replyId) {
    // This would typically make an AJAX call to get reply details
    // For now, we'll show a simple message
    document.getElementById('replyModalBody').innerHTML = '<p>Reply details for ID: ' + replyId + '</p>';
    $('#replyModal').modal('show');
}
</script>
@endsection
