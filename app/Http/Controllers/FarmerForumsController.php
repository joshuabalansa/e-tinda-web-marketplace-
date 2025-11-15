<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerForumsController extends Controller
{
    /**
     * Display farmer forums.
     */
    public function index()
    {
        $forums = Forum::active()->with('user')->latest()->paginate(10);

        return view('farmer.forums.index', compact('forums'));
    }

    /**
     * Display the specified forum.
     */
    public function show(Forum $forum)
    {
        $forum->load(['user', 'replies' => function($query) {
            $query->active()->with('user');
        }]);

        // Increment view count
        $forum->increment('views');

        return view('farmer.forums.show', compact('forum'));
    }

    /**
     * Reply to a forum topic.
     */
    public function reply(Request $request, Forum $forum)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $reply = new ForumReply();
        $reply->forum_id = $forum->id;
        $reply->user_id = Auth::id();
        $reply->content = $request->content;
        $reply->save();

        return redirect()->back()
            ->with('success', 'Reply posted successfully!');
    }
}
