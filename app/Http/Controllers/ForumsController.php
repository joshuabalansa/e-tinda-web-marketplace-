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
        $forums = Forum::active()->with('user')->latest()->paginate(10);
        $categories = Forum::active()->select('category')->distinct()->pluck('category');

        // Get harvest calendar data for current month
        $currentMonth = (int) date('n');
        $harvestCalendar = $this->getHarvestCalendarDataForMonth($currentMonth);

        return view('forums.index', compact('forums', 'categories', 'harvestCalendar', 'currentMonth'));
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

        // Check image file sizes before validation
        if ($request->hasFile('images')) {
            $maxImageSize = config('upload.max_image_size', 10 * 1024 * 1024);
            foreach ($request->file('images') as $image) {
                if ($image->getSize() > $maxImageSize) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['images' => 'Image file size must be less than ' . round($maxImageSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($image->getSize() / (1024 * 1024), 2) . 'MB']);
                }
            }
        }

        $validationRules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:' . implode(',', config('upload.allowed_video_types', ['mp4', 'avi', 'mov', 'wmv'])) . '|max:' . (config('upload.max_video_size', 50 * 1024 * 1024) / 1024), // Get from config
            'images.*' => 'nullable|image|mimes:' . implode(',', config('upload.allowed_image_types', ['jpg', 'jpeg', 'png', 'gif', 'webp'])) . '|max:' . (config('upload.max_image_size', 10 * 1024 * 1024) / 1024),
        ];

        // Add harvest calendar validation if is_harvest_post is checked
        // Only farmers can post to harvest calendar
        if ($request->has('is_harvest_post') && $request->is_harvest_post) {
            // Check if user is a farmer
            if (!Auth::user()->isFarmer()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['is_harvest_post' => 'Only farmers can post to the harvest calendar.']);
            }

            $validationRules['product_name'] = 'required|string|max:255';
            $validationRules['harvest_start_date'] = 'required|date';
            $validationRules['harvest_end_date'] = 'required|date|after_or_equal:harvest_start_date';
            $validationRules['harvest_season'] = 'nullable|string|max:255';
            $validationRules['product_category'] = 'nullable|string|max:255';
        }

        $request->validate($validationRules);

        $forum = new Forum();
        $forum->user_id = Auth::id();
        $forum->title = $request->title;
        $forum->content = $request->content;
        $forum->category = $request->category;

        // Handle harvest calendar fields
        if ($request->has('is_harvest_post') && $request->is_harvest_post) {
            $forum->is_harvest_post = true;
            $forum->product_name = $request->product_name;
            $forum->harvest_start_date = $request->harvest_start_date;
            $forum->harvest_end_date = $request->harvest_end_date;
            $forum->harvest_season = $request->harvest_season;
            $forum->product_category = $request->product_category;
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs(config('upload.video_storage_path', 'forums/videos'), $videoName, config('upload.storage_disk', 'public'));

            $forum->video_path = $videoPath;
            $forum->video_original_name = $video->getClientOriginalName();
        }

        // Handle image uploads (multiple images)
        if ($request->hasFile('images')) {
            $imagePaths = [];
            $imageNames = [];

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs(config('upload.image_storage_path', 'forums/images'), $imageName, config('upload.storage_disk', 'public'));

                $imagePaths[] = $imagePath;
                $imageNames[] = $image->getClientOriginalName();
            }

            // Store as JSON for multiple images
            $forum->image_path = json_encode($imagePaths);
            $forum->image_original_name = json_encode($imageNames);
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
        $forum = Forum::active()->with(['user', 'replies' => function($query) {
            $query->active()->with('user');
        }])->findOrFail($id);

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

        // Check image file sizes before validation
        if ($request->hasFile('images')) {
            $maxImageSize = config('upload.max_image_size', 10 * 1024 * 1024);
            foreach ($request->file('images') as $image) {
                if ($image->getSize() > $maxImageSize) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['images' => 'Image file size must be less than ' . round($maxImageSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($image->getSize() / (1024 * 1024), 2) . 'MB']);
                }
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:' . implode(',', config('upload.allowed_video_types', ['mp4', 'avi', 'mov', 'wmv'])) . '|max:' . (config('upload.max_video_size', 50 * 1024 * 1024) / 1024), // Get from config
            'images.*' => 'nullable|image|mimes:' . implode(',', config('upload.allowed_image_types', ['jpg', 'jpeg', 'png', 'gif', 'webp'])) . '|max:' . (config('upload.max_image_size', 10 * 1024 * 1024) / 1024),
        ]);

        $forum->title = $request->title;
        $forum->content = $request->content;
        $forum->category = $request->category;

        // Handle image uploads (multiple images)
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            $oldImagePaths = $forum->getImagePaths();
            foreach ($oldImagePaths as $oldImagePath) {
                if ($oldImagePath && Storage::disk(config('upload.storage_disk', 'public'))->exists($oldImagePath)) {
                    try {
                        Storage::disk(config('upload.storage_disk', 'public'))->delete($oldImagePath);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old image file during update: ' . $e->getMessage());
                    }
                }
            }

            $imagePaths = [];
            $imageNames = [];

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs(config('upload.image_storage_path', 'forums/images'), $imageName, config('upload.storage_disk', 'public'));

                $imagePaths[] = $imagePath;
                $imageNames[] = $image->getClientOriginalName();
            }

            // Store as JSON for multiple images
            $forum->image_path = json_encode($imagePaths);
            $forum->image_original_name = json_encode($imageNames);
        } else {
            // If no new images are uploaded, preserve the existing image data
            if ($request->has('existing_image_path') && $request->has('existing_image_name')) {
                $forum->image_path = $request->existing_image_path;
                $forum->image_original_name = $request->existing_image_name;
            }
        }

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

        // Delete image files if they exist
        $imagePaths = $forum->getImagePaths();
        foreach ($imagePaths as $imagePath) {
            if ($imagePath && Storage::disk(config('upload.storage_disk', 'public'))->exists($imagePath)) {
                Storage::disk(config('upload.storage_disk', 'public'))->delete($imagePath);
            }
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

    /**
     * Display the full harvest calendar page.
     *
     * @return \Illuminate\View\View
     */
    public function harvestCalendar()
    {
        $currentMonth = (int) date('n');
        $harvestCalendar = $this->getHarvestCalendarDataForMonth($currentMonth);
        $allMonthsData = $this->getAllMonthsHarvestData();

        return view('forums.harvest-calendar', compact('harvestCalendar', 'currentMonth', 'allMonthsData'));
    }

    /**
     * Get harvest calendar data as JSON (AJAX endpoint).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHarvestCalendarData(Request $request)
    {
        $month = $request->input('month', (int) date('n'));
        $data = $this->getHarvestCalendarDataForMonth($month);

        return response()->json($data);
    }

    /**
     * Get harvest posts for a specific month.
     *
     * @param  int  $month
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function getHarvestByMonth($month)
    {
        $month = (int) $month;
        if ($month < 1 || $month > 12) {
            $month = (int) date('n');
        }

        $harvestPosts = Forum::active()
            ->harvestPosts()
            ->byMonth($month)
            ->with('user')
            ->orderBy('harvest_start_date')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'posts' => $harvestPosts->map(function($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'product_name' => $post->product_name,
                        'product_category' => $post->product_category,
                        'harvest_start_date' => $post->harvest_start_date?->format('Y-m-d'),
                        'harvest_end_date' => $post->harvest_end_date?->format('Y-m-d'),
                        'harvest_season' => $post->harvest_season,
                        'user' => $post->user->name,
                        'url' => route('forums.topic', $post->id),
                    ];
                })
            ]);
        }

        return view('forums.harvest-month', compact('harvestPosts', 'month'));
    }

    /**
     * Get harvest calendar data for a specific month.
     *
     * @param  int  $month
     * @return array
     */
    private function getHarvestCalendarDataForMonth($month)
    {
        $harvestPosts = Forum::active()
            ->harvestPosts()
            ->byMonth($month)
            ->with('user')
            ->orderBy('product_name')
            ->get();

        $productsByCategory = [];
        foreach ($harvestPosts as $post) {
            $category = $post->product_category ?: 'Other';
            if (!isset($productsByCategory[$category])) {
                $productsByCategory[$category] = [];
            }
            $productsByCategory[$category][] = [
                'id' => $post->id,
                'product_name' => $post->product_name,
                'harvest_start_date' => $post->harvest_start_date?->format('M d'),
                'harvest_end_date' => $post->harvest_end_date?->format('M d'),
                'harvest_season' => $post->harvest_season,
                'user' => $post->user->name,
                'url' => route('forums.topic', $post->id),
            ];
        }

        return [
            'month' => $month,
            'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
            'products_by_category' => $productsByCategory,
            'total_products' => $harvestPosts->count(),
        ];
    }

    /**
     * Get harvest data for all 12 months.
     *
     * @return array
     */
    private function getAllMonthsHarvestData()
    {
        $allData = [];
        for ($month = 1; $month <= 12; $month++) {
            $allData[$month] = $this->getHarvestCalendarDataForMonth($month);
        }
        return $allData;
    }
}
