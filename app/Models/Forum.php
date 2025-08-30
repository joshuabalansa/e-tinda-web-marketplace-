<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'video_path',
        'video_original_name',
        'views',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete video file when forum is deleted
        static::deleting(function ($forum) {
            if ($forum->video_path && Storage::disk('public')->exists($forum->video_path)) {
                Storage::disk('public')->delete($forum->video_path);
            }
        });
    }

    /**
     * Get the user that created the forum topic.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the replies for the forum topic.
     */
    public function replies()
    {
        return $this->hasMany(ForumReply::class);
    }

    /**
     * Check if the forum has a video attachment.
     */
    public function hasVideo()
    {
        return !empty($this->video_path) && $this->videoFileExists();
    }

    /**
     * Check if the video file actually exists in storage.
     */
    public function videoFileExists()
    {
        if (empty($this->video_path)) {
            return false;
        }

        try {
            return Storage::disk('public')->exists($this->video_path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the video URL for display.
     */
    public function getVideoUrlAttribute()
    {
        if ($this->hasVideo()) {
            try {
                // Use Storage::url() for proper URL generation
                return Storage::disk('public')->url($this->video_path);
            } catch (\Exception $e) {
                \Log::error('Error generating video URL', [
                    'forum_id' => $this->id,
                    'video_path' => $this->video_path,
                    'error' => $e->getMessage()
                ]);
                // Fallback to asset() method
                return asset('storage/' . $this->video_path);
            }
        }
        return null;
    }

    /**
     * Check if video is available and accessible.
     */
    public function isVideoAccessible()
    {
        if (!$this->hasVideo()) {
            return false;
        }

        try {
            $url = asset('storage/' . $this->video_path);
            $headers = get_headers($url);
            return $headers && strpos($headers[0], '200') !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get video file size in human readable format.
     */
    public function getVideoFileSizeAttribute()
    {
        if (!$this->hasVideo()) {
            return null;
        }

        try {
            $size = Storage::disk('public')->size($this->video_path);
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($size >= 1024 && $i < count($units) - 1) {
                $size /= 1024;
                $i++;
            }
            return round($size, 2) . ' ' . $units[$i];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get video MIME type for proper video element support.
     */
    public function getVideoMimeTypeAttribute()
    {
        if (!$this->hasVideo()) {
            return null;
        }

        $extension = pathinfo($this->video_path, PATHINFO_EXTENSION);
        $mimeTypes = [
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'wmv' => 'video/x-ms-wmv',
            'flv' => 'video/x-flv',
            'webm' => 'video/webm',
            'mkv' => 'video/x-matroska'
        ];

        return $mimeTypes[strtolower($extension)] ?? 'video/mp4';
    }

    /**
     * Clean up orphaned video records (where file doesn't exist).
     */
    public static function cleanupOrphanedVideos()
    {
        $forums = self::whereNotNull('video_path')->get();

        foreach ($forums as $forum) {
            if (!$forum->videoFileExists()) {
                $forum->update([
                    'video_path' => null,
                    'video_original_name' => null
                ]);
            }
        }
    }

    /**
     * Check if video should be preserved during update.
     */
    public function shouldPreserveVideo($existingVideoPath = null)
    {
        // If we have a current video and no new video is being uploaded
        if ($this->hasVideo() && !$existingVideoPath) {
            return true;
        }

        // If the existing video path matches the current one
        if ($existingVideoPath && $this->video_path === $existingVideoPath) {
            return true;
        }

        return false;
    }

    /**
     * Safely update video information.
     */
    public function updateVideo($newVideoPath = null, $newVideoName = null, $existingVideoPath = null)
    {
        if ($newVideoPath && $newVideoName) {
            // New video uploaded
            $this->video_path = $newVideoPath;
            $this->video_original_name = $newVideoName;
        } elseif ($existingVideoPath && $existingVideoName) {
            // Preserve existing video from hidden fields
            $this->video_path = $existingVideoPath;
            $this->video_original_name = $existingVideoName;
        }
        // If neither new video nor existing video, leave as null (will be cleaned up)

        return true;
    }
}
