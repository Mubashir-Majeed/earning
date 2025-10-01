<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;

class FixVideoIds extends Command
{
    protected $signature = 'videos:fix-ids';
    protected $description = 'Fix YouTube IDs for all videos';

    public function handle()
    {
        $videos = Video::all();
        $updated = 0;
        
        foreach ($videos as $video) {
            $correctId = Video::extractYoutubeId($video->youtube_url);
            
            if ($correctId && $video->youtube_id !== $correctId) {
                $video->update(['youtube_id' => $correctId]);
                $this->info("Updated video {$video->id} ({$video->title}) with ID: {$correctId}");
                $updated++;
            } else {
                $this->line("Video {$video->id} ({$video->title}) already has correct ID: {$video->youtube_id}");
            }
        }
        
        $this->info("Fixed {$updated} videos");
        return 0;
    }
}
