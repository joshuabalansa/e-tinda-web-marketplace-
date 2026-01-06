<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\DatabaseCompatibility;

class Forum extends Model
{
    use HasFactory, DatabaseCompatibility;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'video_path',
        'video_original_name',
        'image_path',
        'image_original_name',
        'views',
        'status',
        'is_flagged',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
        'product_name',
        'harvest_start_date',
        'harvest_end_date',
        'harvest_season',
        'is_harvest_post',
        'product_category',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'moderated_at' => 'datetime',
        'is_flagged' => 'boolean',
        'views' => 'integer',
        'harvest_start_date' => 'date',
        'harvest_end_date' => 'date',
        'is_harvest_post' => 'boolean',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete video and image files when forum is deleted
        static::deleting(function ($forum) {
            if ($forum->video_path && Storage::disk('public')->exists($forum->video_path)) {
                Storage::disk('public')->delete($forum->video_path);
            }
            if ($forum->image_path && Storage::disk('public')->exists($forum->image_path)) {
                Storage::disk('public')->delete($forum->image_path);
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
     * Get the moderator who moderated this forum.
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Get the replies for the forum topic.
     */
    public function replies()
    {
        return $this->hasMany(ForumReply::class);
    }

    /**
     * Scope to get only active forums.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get flagged forums.
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Scope to get forums by status.
     */
    public function scopeByStatus($query, $status)
    {
        if (!$this->validateStatus($status)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
        return $query->where('status', $status);
    }

    /**
     * Set status with validation
     */
    public function setStatus(string $status): bool
    {
        if (!$this->validateStatus($status)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->status = $status;
        return $this->save();
    }

    /**
     * Check if forum is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if forum is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if forum is hidden
     */
    public function isHidden(): bool
    {
        return $this->status === 'hidden';
    }

    /**
     * Check if forum is deleted
     */
    public function isDeleted(): bool
    {
        return $this->status === 'deleted';
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
                // Fallback to storage route
                return url('storage/' . $this->video_path);
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
            $url = url('storage/' . $this->video_path);
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

    /**
     * Check if the forum has an image attachment.
     */
    public function hasImage()
    {
        if (empty($this->image_path)) {
            return false;
        }

        $imagePaths = $this->getImagePaths();
        return !empty($imagePaths) && count($imagePaths) > 0;
    }

    /**
     * Get image paths as array (handles JSON encoding)
     */
    public function getImagePaths()
    {
        if (empty($this->image_path)) {
            return [];
        }

        $paths = json_decode($this->image_path, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($paths)) {
            return $paths;
        }

        // Fallback: treat as single path string
        return [$this->image_path];
    }

    /**
     * Get image original names as array
     */
    public function getImageOriginalNames()
    {
        if (empty($this->image_original_name)) {
            return [];
        }

        $names = json_decode($this->image_original_name, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($names)) {
            return $names;
        }

        // Fallback: treat as single name string
        return [$this->image_original_name];
    }

    /**
     * Get all image URLs for display.
     */
    public function getImageUrlsAttribute()
    {
        $urls = [];
        $imagePaths = $this->getImagePaths();

        foreach ($imagePaths as $imagePath) {
            try {
                if (Storage::disk('public')->exists($imagePath)) {
                    $urls[] = Storage::disk('public')->url($imagePath);
                }
            } catch (\Exception $e) {
                \Log::error('Error generating image URL', [
                    'forum_id' => $this->id,
                    'image_path' => $imagePath,
                    'error' => $e->getMessage()
                ]);
                // Fallback to storage route
                $urls[] = url('storage/' . $imagePath);
            }
        }

        return $urls;
    }

    /**
     * Check if the image file actually exists in storage.
     */
    public function imageFileExists()
    {
        if (empty($this->image_path)) {
            return false;
        }

        $imagePaths = $this->getImagePaths();
        foreach ($imagePaths as $imagePath) {
            try {
                if (Storage::disk('public')->exists($imagePath)) {
                    return true;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return false;
    }

    /**
     * Scope to get only harvest calendar posts.
     */
    public function scopeHarvestPosts($query)
    {
        return $query->where('is_harvest_post', true);
    }

    /**
     * Scope to filter by season.
     */
    public function scopeBySeason($query, $season)
    {
        return $query->where('harvest_season', $season);
    }

    /**
     * Scope to filter by month (1-12).
     */
    public function scopeByMonth($query, $month)
    {
        return $query->where(function($q) use ($month) {
            // Normal case: harvest within same year (e.g., March to June)
            // Month is between start and end month
            $q->where(function($q1) use ($month) {
                $q1->whereRaw('MONTH(harvest_start_date) <= ?', [$month])
                   ->whereRaw('MONTH(harvest_end_date) >= ?', [$month]);
            })
            // Year-spanning case: harvest crosses year boundary (e.g., November to February)
            // Month is either >= start_month OR <= end_month
            ->orWhere(function($q2) use ($month) {
                $q2->whereRaw('MONTH(harvest_start_date) > MONTH(harvest_end_date)')
                   ->where(function($q3) use ($month) {
                       $q3->whereRaw('MONTH(harvest_start_date) <= ?', [$month])
                          ->orWhereRaw('MONTH(harvest_end_date) >= ?', [$month]);
                   });
            });
        });
    }

    /**
     * Get array of months when product is available.
     */
    public function getHarvestMonthsAttribute()
    {
        if (!$this->harvest_start_date || !$this->harvest_end_date) {
            return [];
        }

        $start = $this->harvest_start_date;
        $end = $this->harvest_end_date;
        $months = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $months[] = (int) $current->format('n'); // 1-12
            $current->addMonth();
        }

        // Handle year-spanning harvests (e.g., November to February)
        if ($start->month > $end->month) {
            // Harvest spans across year boundary
            for ($m = $start->month; $m <= 12; $m++) {
                $months[] = $m;
            }
            for ($m = 1; $m <= $end->month; $m++) {
                $months[] = $m;
            }
            $months = array_unique($months);
        }

        return array_unique($months);
    }
}
