<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumsController extends Controller
{
    /**
     * Display a listing of the forum topics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $forums = Forum::with('user')->latest()->paginate(10);
        $categories = Forum::select('category')->distinct()->pluck('category');

        return view('forums.index', compact('forums', 'categories'));
    }

    /**
     * Show the form for creating a new forum topic.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('forums.create');
    }

    /**
     * Store a newly created forum topic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
        ]);

        $forum = new Forum();
        $forum->user_id = Auth::id();
        $forum->title = $request->title;
        $forum->content = $request->content;
        $forum->category = $request->category;
        $forum->save();

        return redirect()->route('forums.topic', $forum->id)
            ->with('success', 'Topic created successfully!');
    }

    /**
     * Display the specified forum topic.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $forum = Forum::with(['user', 'replies.user'])->findOrFail($id);

        // Increment view count
        $forum->increment('views');

        return view('forums.topic', compact('forum'));
    }

    /**
     * Show the form for editing the specified forum topic.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $forum = Forum::findOrFail($id);

        // Check if user is authorized to edit
        if (Auth::id() !== $forum->user_id) {
            return redirect()->route('forums.topic', $id)
                ->with('error', 'You are not authorized to edit this topic.');
        }

        return view('forums.edit', compact('forum'));
    }

    /**
     * Update the specified forum topic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $forum = Forum::findOrFail($id);

        // Check if user is authorized to update
        if (Auth::id() !== $forum->user_id) {
            return redirect()->route('forums.topic', $id)
                ->with('error', 'You are not authorized to update this topic.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
        ]);

        $forum->title = $request->title;
        $forum->content = $request->content;
        $forum->category = $request->category;
        $forum->save();

        return redirect()->route('forums.topic', $forum->id)
            ->with('success', 'Topic updated successfully!');
    }

    /**
     * Remove the specified forum topic from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $forum = Forum::findOrFail($id);

        // Check if user is authorized to delete
        if (Auth::id() !== $forum->user_id) {
            return redirect()->route('forums.topic', $id)
                ->with('error', 'You are not authorized to delete this topic.');
        }

        $forum->delete();

        return redirect()->route('forums.index')
            ->with('success', 'Topic deleted successfully!');
    }

    /**
     * Store a new reply to a forum topic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $reply = new ForumReply();
        $reply->forum_id = $id;
        $reply->user_id = Auth::id();
        $reply->content = $request->content;
        $reply->save();

        return redirect()->route('forums.topic', $id)
            ->with('success', 'Reply posted successfully!');
    }

    /**
     * Mark a reply as helpful.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markHelpful($id)
    {
        $reply = ForumReply::findOrFail($id);
        $reply->increment('helpful_votes');

        return redirect()->back()
            ->with('success', 'Reply marked as helpful!');
    }
}
