<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // Check file size before validation to provide better error messages
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $maxSize = config('upload.max_video_size', 50 * 1024 * 1024); // Get from config

            if ($video->getSize() > $maxSize) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Video file size must be less than ' . round($maxSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($video->getSize() / (1024 * 1024), 2) . 'MB']);
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:' . implode(',', config('upload.allowed_video_types', ['mp4', 'avi', 'mov', 'wmv'])) . '|max:' . (config('upload.max_video_size', 50 * 1024 * 1024) / 1024), // Get from config
        ]);

        $forum = new Forum();
        $forum->user_id = Auth::id();
        $forum->title = $request->title;
        $forum->content = $request->content;
        $forum->category = $request->category;

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs(config('upload.video_storage_path', 'forums/videos'), $videoName, config('upload.storage_disk', 'public'));

            $forum->video_path = $videoPath;
            $forum->video_original_name = $video->getClientOriginalName();
        }

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

        // Check file size before validation to provide better error messages
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $maxSize = config('upload.max_video_size', 50 * 1024 * 1024); // Get from config

            if ($video->getSize() > $maxSize) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Video file size must be less than ' . round($maxSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($video->getSize() / (1024 * 1024), 2) . 'MB']);
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:' . implode(',', config('upload.allowed_video_types', ['mp4', 'avi', 'mov', 'wmv'])) . '|max:' . (config('upload.max_video_size', 50 * 1024 * 1024) / 1024), // Get from config
        ]);

        $forum->title = $request->title;
        $forum->content = $request->content;
        $forum->category = $request->category;

        // Handle video upload
        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($forum->video_path && Storage::disk(config('upload.storage_disk', 'public'))->exists($forum->video_path)) {
                try {
                    Storage::disk(config('upload.storage_disk', 'public'))->delete($forum->video_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old video file during update: ' . $e->getMessage());
                }
            }

            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs(config('upload.video_storage_path', 'forums/videos'), $videoName, config('upload.storage_disk', 'public'));

            $forum->video_path = $videoPath;
            $forum->video_original_name = $video->getClientOriginalName();
        } else {
            // If no new video is uploaded, preserve the existing video data
            if ($request->has('existing_video_path') && $request->has('existing_video_name')) {
                $forum->video_path = $request->existing_video_path;
                $forum->video_original_name = $request->existing_video_name;
            }
        }

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

        // Delete video file if exists
        if ($forum->video_path && Storage::disk(config('upload.storage_disk', 'public'))->exists($forum->video_path)) {
            Storage::disk(config('upload.storage_disk', 'public'))->delete($forum->video_path);
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
        // Check file size before validation to provide better error messages
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $maxSize = config('upload.max_video_size', 50 * 1024 * 1024); // Get from config

            if ($video->getSize() > $maxSize) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Video file size must be less than ' . round($maxSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($video->getSize() / (1024 * 1024), 2) . 'MB']);
            }
        }

        $request->validate([
            'content' => 'required|string',
            'video' => 'nullable|file|mimes:' . implode(',', config('upload.allowed_video_types', ['mp4', 'avi', 'mov', 'wmv'])) . '|max:' . (config('upload.max_video_size', 50 * 1024 * 1024) / 1024), // Get from config
        ]);

        $reply = new ForumReply();
        $reply->forum_id = $id;
        $reply->user_id = Auth::id();
        $reply->content = $request->content;

        // Handle video upload for reply
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs(config('upload.video_storage_path', 'forums/videos') . '/replies', $videoName, config('upload.storage_disk', 'public'));

            $reply->video_path = $videoPath;
            $reply->video_original_name = $video->getClientOriginalName();
        }

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

    /**
     * Delete a reply.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteReply($id)
    {
        $reply = ForumReply::findOrFail($id);

        // Check if user is authorized to delete
        if (Auth::id() !== $reply->user_id) {
            return redirect()->back()
                ->with('error', 'You are not authorized to delete this reply.');
        }

        $forumId = $reply->forum_id;

        // Delete video file if exists
        if ($reply->video_path && Storage::disk(config('upload.storage_disk', 'public'))->exists($reply->video_path)) {
            Storage::disk(config('upload.storage_disk', 'public'))->delete($reply->video_path);
        }

        $reply->delete();

        return redirect()->route('forums.topic', $forumId)
            ->with('success', 'Reply deleted successfully!');
    }

    /**
     * Clean up orphaned video records.
     */
    public function cleanupOrphanedVideos()
    {
        Forum::cleanupOrphanedVideos();
        ForumReply::cleanupOrphanedVideos();

        return redirect()->back()->with('success', 'Orphaned video records cleaned up successfully.');
    }

    /**
     * Test video functionality with a sample video.
     */
    public function testVideo()
    {
        // Create a test forum with video for demonstration
        $testForum = new Forum();
        $testForum->user_id = auth()->id() ?? 1;
        $testForum->title = 'Test Forum with Video';
        $testForum->content = 'This is a test forum to demonstrate video functionality.';
        $testForum->category = 'Crop Farming';
        $testForum->video_path = 'test/sample_video.mp4';
        $testForum->video_original_name = 'sample_video.mp4';
        $testForum->save();

        return redirect()->route('forums.topic', $testForum->id)
            ->with('success', 'Test forum created with video demonstration.');
    }
}
