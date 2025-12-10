<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\DatabaseCompatibility;

class ForumReply extends Model
{
    use HasFactory, DatabaseCompatibility;

    protected $fillable = [
        'forum_id',
        'user_id',
        'content',
        'video_path',
        'video_original_name',
        'helpful_votes',
        'status',
        'is_flagged',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'moderated_at' => 'datetime',
        'is_flagged' => 'boolean',
        'helpful_votes' => 'integer',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete video file when reply is deleted
        static::deleting(function ($reply) {
            if ($reply->video_path && Storage::disk('public')->exists($reply->video_path)) {
                Storage::disk('public')->delete($reply->video_path);
            }
        });
    }

    /**
     * Get the forum that the reply belongs to.
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Get the user that created the reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the moderator who moderated this reply.
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Scope to get only active replies.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get flagged replies.
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Scope to get replies by status.
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
     * Check if reply is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if reply is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if reply is hidden
     */
    public function isHidden(): bool
    {
        return $this->status === 'hidden';
    }

    /**
     * Check if reply is deleted
     */
    public function isDeleted(): bool
    {
        return $this->status === 'deleted';
    }

    /**
     * Check if the reply has a video attachment.
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
                \Log::error('Error generating reply video URL', [
                    'reply_id' => $this->id,
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
        $replies = self::whereNotNull('video_path')->get();

        foreach ($replies as $reply) {
            if (!$reply->videoFileExists()) {
                $reply->update([
                    'video_path' => null,
                    'video_original_name' => null
                ]);
            }
        }
    }
}
