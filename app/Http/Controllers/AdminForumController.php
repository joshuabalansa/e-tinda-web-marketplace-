<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminForumController extends Controller
{
    /**
     * Display the forum moderation dashboard.
     */
    public function index(Request $request)
    {
        $query = Forum::with(['user', 'replies', 'moderator']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by flagged status
        if ($request->has('flagged') && $request->flagged) {
            $query->where('is_flagged', true);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $forums = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_forums' => Forum::count(),
            'active_forums' => Forum::where('status', 'active')->count(),
            'pending_forums' => Forum::where('status', 'pending')->count(),
            'hidden_forums' => Forum::where('status', 'hidden')->count(),
            'flagged_forums' => Forum::where('is_flagged', true)->count(),
            'total_replies' => ForumReply::count(),
            'flagged_replies' => ForumReply::where('is_flagged', true)->count(),
        ];

        return view('admin.forums.index', compact('forums', 'stats'));
    }

    /**
     * Show a specific forum for moderation.
     */
    public function show(Forum $forum)
    {
        $forum->load(['user', 'replies.user', 'moderator']);

        $stats = [
            'total_replies' => $forum->replies->count(),
            'active_replies' => $forum->replies->where('status', 'active')->count(),
            'flagged_replies' => $forum->replies->where('is_flagged', true)->count(),
            'helpful_votes' => $forum->replies->sum('helpful_votes'),
        ];

        return view('admin.forums.show', compact('forum', 'stats'));
    }

    /**
     * Update forum status (approve, hide, delete).
     */
    public function updateStatus(Request $request, Forum $forum)
    {
        $request->validate([
            'status' => 'required|in:active,pending,hidden,deleted',
            'moderation_notes' => 'nullable|string|max:1000',
        ]);

        $forum->update([
            'status' => $request->status,
            'moderation_notes' => $request->moderation_notes,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
        ]);

        $statusLabels = [
            'active' => 'approved',
            'pending' => 'set to pending',
            'hidden' => 'hidden',
            'deleted' => 'deleted'
        ];

        return redirect()->back()
            ->with('success', "Forum topic has been {$statusLabels[$request->status]} successfully!");
    }

    /**
     * Toggle forum flagged status.
     */
    public function toggleFlag(Forum $forum)
    {
        $forum->update(['is_flagged' => !$forum->is_flagged]);

        $action = $forum->is_flagged ? 'flagged' : 'unflagged';

        return redirect()->back()
            ->with('success', "Forum topic has been {$action} successfully!");
    }

    /**
     * Delete a forum permanently.
     */
    public function destroy(Forum $forum)
    {
        $forum->delete();

        return redirect()->route('admin.forums.index')
            ->with('success', 'Forum topic has been permanently deleted!');
    }

    /**
     * Show all forum replies for moderation.
     */
    public function replies(Request $request)
    {
        $query = ForumReply::with(['user', 'forum', 'moderator']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by flagged status
        if ($request->has('flagged') && $request->flagged) {
            $query->where('is_flagged', true);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('forum', function($forumQuery) use ($search) {
                      $forumQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $replies = $query->latest()->paginate(15);

        return view('admin.forums.replies', compact('replies'));
    }

    /**
     * Update reply status.
     */
    public function updateReplyStatus(Request $request, ForumReply $reply)
    {
        $request->validate([
            'status' => 'required|in:active,pending,hidden,deleted',
            'moderation_notes' => 'nullable|string|max:1000',
        ]);

        $reply->update([
            'status' => $request->status,
            'moderation_notes' => $request->moderation_notes,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
        ]);

        $statusLabels = [
            'active' => 'approved',
            'pending' => 'set to pending',
            'hidden' => 'hidden',
            'deleted' => 'deleted'
        ];

        return redirect()->back()
            ->with('success', "Reply has been {$statusLabels[$request->status]} successfully!");
    }

    /**
     * Toggle reply flagged status.
     */
    public function toggleReplyFlag(ForumReply $reply)
    {
        $reply->update(['is_flagged' => !$reply->is_flagged]);

        $action = $reply->is_flagged ? 'flagged' : 'unflagged';

        return redirect()->back()
            ->with('success', "Reply has been {$action} successfully!");
    }

    /**
     * Delete a reply permanently.
     */
    public function destroyReply(ForumReply $reply)
    {
        $reply->delete();

        return redirect()->back()
            ->with('success', 'Reply has been permanently deleted!');
    }

    /**
     * Bulk actions for forums.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,hide,delete,flag,unflag',
            'forum_ids' => 'required|array',
            'forum_ids.*' => 'exists:forums,id',
        ]);

        $forums = Forum::whereIn('id', $request->forum_ids);
        $count = $forums->count();

        switch ($request->action) {
            case 'approve':
                $forums->update([
                    'status' => 'active',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);
                $message = "{$count} forum(s) approved successfully!";
                break;

            case 'hide':
                $forums->update([
                    'status' => 'hidden',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);
                $message = "{$count} forum(s) hidden successfully!";
                break;

            case 'delete':
                $forums->update([
                    'status' => 'deleted',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);
                $message = "{$count} forum(s) deleted successfully!";
                break;

            case 'flag':
                $forums->update(['is_flagged' => true]);
                $message = "{$count} forum(s) flagged successfully!";
                break;

            case 'unflag':
                $forums->update(['is_flagged' => false]);
                $message = "{$count} forum(s) unflagged successfully!";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk actions for replies.
     */
    public function bulkReplyAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,hide,delete,flag,unflag',
            'reply_ids' => 'required|array',
            'reply_ids.*' => 'exists:forum_replies,id',
        ]);

        $replies = ForumReply::whereIn('id', $request->reply_ids);
        $count = $replies->count();

        switch ($request->action) {
            case 'approve':
                $replies->update([
                    'status' => 'active',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);
                $message = "{$count} reply(ies) approved successfully!";
                break;

            case 'hide':
                $replies->update([
                    'status' => 'hidden',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);
                $message = "{$count} reply(ies) hidden successfully!";
                break;

            case 'delete':
                $replies->update([
                    'status' => 'deleted',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);
                $message = "{$count} reply(ies) deleted successfully!";
                break;

            case 'flag':
                $replies->update(['is_flagged' => true]);
                $message = "{$count} reply(ies) flagged successfully!";
                break;

            case 'unflag':
                $replies->update(['is_flagged' => false]);
                $message = "{$count} reply(ies) unflagged successfully!";
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}