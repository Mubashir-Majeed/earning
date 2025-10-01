<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoWatch;
use App\Services\VideoEarningService;
use Carbon\Carbon;

class VideoController extends Controller
{
    protected $videoEarningService;

    public function __construct(VideoEarningService $videoEarningService)
    {
        $this->videoEarningService = $videoEarningService;
    }

    public function index()
    {
        $user = auth()->user();
        
        if (!$user->canAccessTasks()) {
            return redirect()->route('deposit')->with('error', 'Please make your initial deposit to access video tasks.');
        }

        // Show all active videos instead of just assigned tasks
        $videos = Video::active()
            ->orderBy('created_at', 'desc')
            ->get();

        // Create virtual tasks for display purposes
        $todayTasks = $videos->map(function($video) use ($user) {
            // Check if user has completed this video today
            $completedTask = $user->videoTasks()
                ->where('video_id', $video->id)
                ->where('assigned_date', Carbon::today())
                ->where('is_completed', true)
                ->first();

            return (object) [
                'video' => $video,
                'is_completed' => $completedTask ? true : false,
                'points_earned' => $video->points_value,
                'assigned_date' => Carbon::today(),
            ];
        });
        
        return view('videos.index', compact('todayTasks'));
    }

    public function show(Video $video)
    {
        $user = auth()->user();
        
        if (!$user->canAccessTasks()) {
            return redirect()->route('deposit')->with('error', 'Please make your initial deposit to access video tasks.');
        }

        // Check if video is active
        if (!$video->is_active) {
            return redirect()->route('videos.index')->with('error', 'This video is not available.');
        }

        // Check if user has completed this video today
        $task = $user->videoTasks()
            ->where('video_id', $video->id)
            ->where('assigned_date', Carbon::today())
            ->first();

        // Create virtual task if not exists
        if (!$task) {
            $task = (object) [
                'video_id' => $video->id,
                'is_completed' => false,
                'points_earned' => $video->points_value,
                'assigned_date' => Carbon::today(),
            ];
        }

        if ($task->is_completed) {
            return redirect()->route('videos.index')->with('info', 'You have already completed this video task.');
        }

        // Check if user is already watching this video
        $activeWatch = VideoWatch::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->where('is_completed', false)
            ->first();

        return view('videos.show', compact('video', 'task', 'activeWatch'));
    }

    public function startWatch(Video $video)
    {
        $user = auth()->user();
        
        // Check if video is active
        if (!$video->is_active) {
            return response()->json(['error' => 'Video is not available'], 404);
        }

        // Check if user has already completed this video today
        $completedTask = $user->videoTasks()
            ->where('video_id', $video->id)
            ->where('assigned_date', Carbon::today())
            ->where('is_completed', true)
            ->first();

        if ($completedTask) {
            return response()->json(['error' => 'You have already completed this video today'], 400);
        }

        // Check if user is already watching this video
        $existingWatch = VideoWatch::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->where('is_completed', false)
            ->first();

        if ($existingWatch) {
            return response()->json(['watch_id' => $existingWatch->id]);
        }

        // Start new watch
        $watch = $this->videoEarningService->startVideoWatch($user, $video);

        return response()->json(['watch_id' => $watch->id]);
    }

    public function completeWatch(Request $request, Video $video)
    {
        $user = auth()->user();
        
        $request->validate([
            'watch_id' => 'required|exists:video_watches,id',
            'watch_duration' => 'required|integer|min:1',
            'required_duration' => 'required|integer|min:1'
        ]);

        $watch = VideoWatch::where('id', $request->watch_id)
            ->where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->where('is_completed', false)
            ->first();

        if (!$watch) {
            return response()->json(['error' => 'Watch session not found'], 404);
        }

        // Validate watch duration - user must watch at least 80% of video
        $watchDuration = $request->watch_duration;
        $requiredDuration = $request->required_duration;
        
        if ($watchDuration < $requiredDuration) {
            return response()->json([
                'error' => 'Insufficient watch time',
                'message' => "You need to watch at least {$requiredDuration} seconds to earn points. You watched {$watchDuration} seconds."
            ], 400);
        }

        // Check if user already completed this video today
        $existingTask = $user->videoTasks()
            ->where('video_id', $video->id)
            ->where('assigned_date', Carbon::today())
            ->where('is_completed', true)
            ->first();

        if ($existingTask) {
            return response()->json([
                'error' => 'Already completed',
                'message' => 'You have already completed this video today.'
            ], 400);
        }

        $completed = $this->videoEarningService->completeVideoWatch($watch, $watchDuration);

        if ($completed) {
            return response()->json([
                'success' => true,
                'points_earned' => $video->points_value,
                'dollar_value' => $this->videoEarningService->calculateDollarValue($video->points_value),
                'watch_duration' => $watchDuration,
                'required_duration' => $requiredDuration
            ]);
        }

        return response()->json(['error' => 'Failed to complete video watch'], 400);
    }

    public function categories()
    {
        $categories = Video::active()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('videos.categories', compact('categories'));
    }

    public function byCategory($category)
    {
        $videos = Video::active()
            ->byCategory($category)
            ->paginate(12);

        return view('videos.category', compact('videos', 'category'));
    }
}
