<?php

namespace App\Services;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoTask;
use App\Models\VideoWatch;
use App\Models\UserEarning;
use Carbon\Carbon;

class VideoEarningService
{
    const POINTS_TO_DOLLAR_RATIO = 750 / 80; // 750 points = $80

    public function assignDailyTasks(User $user): void
    {
        if (!$user->canAccessTasks()) {
            return;
        }

        $today = Carbon::today();
        
        // Check if user already has tasks for today
        $existingTasks = VideoTask::where('user_id', $user->id)
            ->where('assigned_date', $today)
            ->count();

        if ($existingTasks > 0) {
            return; // Already assigned tasks for today
        }

        // Get daily quota by level
        $dailyQuota = method_exists($user, 'requiredDailyVideos') ? $user->requiredDailyVideos() : 5;

        // Get random active videos up to quota
        $videos = Video::active()
            ->where(function($query) use ($today) {
                $query->where('assigned_date', '<=', $today)
                      ->orWhereNull('assigned_date');
            })
            ->inRandomOrder()
            ->limit($dailyQuota)
            ->get();

        foreach ($videos as $video) {
            VideoTask::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'assigned_date' => $today,
                'points_earned' => $video->points_value,
            ]);
        }
    }

    public function startVideoWatch(User $user, Video $video): VideoWatch
    {
        $watch = VideoWatch::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'watch_started_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $watch;
    }

    public function completeVideoWatch(VideoWatch $watch, int $actualWatchDuration = null): bool
    {
        if ($watch->is_completed) {
            return false;
        }

        // Calculate actual watch duration
        $watchDuration = $actualWatchDuration ?? now()->diffInSeconds($watch->watch_started_at);

        $watch->update([
            'watch_completed_at' => now(),
            'is_completed' => true,
            'watch_duration' => $watchDuration,
        ]);

        // Award points to user
        $this->awardPoints($watch->user, $watch->video, $watch->video->points_value);

        // Mark video task as completed
        $this->markTaskCompleted($watch->user, $watch->video);

        return true;
    }

    protected function awardPoints(User $user, Video $video, int $points): void
    {
        $dollarValue = $this->calculateDollarValue($points);

        // Update user's points and balance
        $user->increment('points', $points);
        $user->increment('balance', $dollarValue);

        // Find the video task
        $videoTask = VideoTask::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->where('assigned_date', Carbon::today())
            ->first();

        // Create earning record
        UserEarning::create([
            'user_id' => $user->id,
            'video_task_id' => $videoTask ? $videoTask->id : null,
            'points_earned' => $points,
            'dollar_value' => $dollarValue,
            'type' => 'video_watch',
            'description' => "Earned from watching: {$video->title}",
            'earned_date' => Carbon::today(),
        ]);
    }

    protected function markTaskCompleted(User $user, Video $video): void
    {
        $task = VideoTask::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->where('assigned_date', Carbon::today())
            ->first();

        if ($task) {
            $task->update([
                'is_completed' => true,
                'completed_at' => now(),
                'points_earned' => $video->points_value,
            ]);
        } else {
            // Create task if it doesn't exist (for videos shown without pre-assignment)
            VideoTask::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'assigned_date' => Carbon::today(),
                'is_completed' => true,
                'completed_at' => now(),
                'points_earned' => $video->points_value,
            ]);
        }
    }

    public function calculateDollarValue(int $points): float
    {
        return round($points / self::POINTS_TO_DOLLAR_RATIO, 2);
    }

    public function getUserDailyTasks(User $user, Carbon $date = null): \Illuminate\Database\Eloquent\Collection
    {
        $date = $date ?? Carbon::today();

        return VideoTask::with(['video'])
            ->where('user_id', $user->id)
            ->where('assigned_date', $date)
            ->orderBy('is_completed')
            ->orderBy('created_at')
            ->get();
    }

    public function getUserStats(User $user): array
    {
        $totalEarnings = $user->earnings()->sum('dollar_value');
        $totalPoints = $user->points;
        $totalVideosWatched = $user->videoWatches()->completed()->count();
        $todayEarnings = $user->earnings()
            ->where('earned_date', Carbon::today())
            ->sum('dollar_value');

        return [
            'total_earnings' => $totalEarnings,
            'total_points' => $totalPoints,
            'total_videos_watched' => $totalVideosWatched,
            'today_earnings' => $todayEarnings,
            'balance' => $user->balance,
        ];
    }
}
