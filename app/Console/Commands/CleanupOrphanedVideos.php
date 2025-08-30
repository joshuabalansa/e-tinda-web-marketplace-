<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Forum;
use App\Models\ForumReply;

class CleanupOrphanedVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:cleanup {--dry-run : Show what would be cleaned up without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned video records where video files no longer exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of orphaned video records...');

        $dryRun = $this->option('dry-run');

        // Clean up forums
        $forums = Forum::whereNotNull('video_path')->get();
        $orphanedForums = 0;

        foreach ($forums as $forum) {
            if (!$forum->videoFileExists()) {
                $orphanedForums++;
                if ($dryRun) {
                    $this->warn("Would clean up forum ID {$forum->id}: {$forum->video_path}");
                } else {
                    $forum->update([
                        'video_path' => null,
                        'video_original_name' => null
                    ]);
                    $this->info("Cleaned up forum ID {$forum->id}: {$forum->video_path}");
                }
            }
        }

        // Clean up replies
        $replies = ForumReply::whereNotNull('video_path')->get();
        $orphanedReplies = 0;

        foreach ($replies as $reply) {
            if (!$reply->videoFileExists()) {
                $orphanedReplies++;
                if ($dryRun) {
                    $this->warn("Would clean up reply ID {$reply->id}: {$reply->video_path}");
                } else {
                    $reply->update([
                        'video_path' => null,
                        'video_original_name' => null
                    ]);
                    $this->info("Cleaned up reply ID {$reply->id}: {$reply->video_path}");
                }
            }
        }

        if ($dryRun) {
            $this->info("Dry run complete. Would clean up {$orphanedForums} forums and {$orphanedReplies} replies.");
        } else {
            $this->info("Cleanup complete. Cleaned up {$orphanedForums} forums and {$orphanedReplies} replies.");
        }

        return 0;
    }
}
