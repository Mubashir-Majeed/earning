<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VideoEarningService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $videoEarningService;

    public function __construct(VideoEarningService $videoEarningService)
    {
        $this->videoEarningService = $videoEarningService;
    }

    public function index()
    {
        $user = auth()->user();
        
        // If user is admin, redirect to admin panel
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        // Assign daily tasks if user can access them
        $this->videoEarningService->assignDailyTasks($user);
        
        // Get user stats
        $stats = $this->videoEarningService->getUserStats($user);
        
        // Get today's tasks
        $todayTasks = $this->videoEarningService->getUserDailyTasks($user);
        
        // Get recent earnings
        $recentEarnings = $user->earnings()
            ->with('videoTask.video')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('user', 'stats', 'todayTasks', 'recentEarnings'));
    }

    public function deposit()
    {
        $user = auth()->user();
        
        if ($user->has_deposited) {
            return redirect()->route('dashboard')->with('info', 'You have already made your initial deposit.');
        }

        return view('deposit');
    }

    public function withdrawal()
    {
        $user = auth()->user();
        $stats = $this->videoEarningService->getUserStats($user);
        return view('withdrawal', compact('user', 'stats'));
    }

    public function earnings()
    {
        $user = auth()->user();
        $stats = $this->videoEarningService->getUserStats($user);
        
        // Get earnings history
        $earnings = $user->earnings()
            ->with('videoTask.video')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get monthly earnings data for chart (sum dollar_value column)
        $monthlyEarnings = $user->earnings()
            ->selectRaw('DATE_FORMAT(earned_date, "%Y-%m") as month, SUM(dollar_value) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('earnings', compact('user', 'stats', 'earnings', 'monthlyEarnings'));
    }

    public function referrals()
    {
        $user = auth()->user();
        $referrals = $user->referrals()->latest()->paginate(12);
        $referralLink = route('register', ['ref' => $user->referral_code]);
        return view('referrals', compact('user', 'referrals', 'referralLink'));
    }

    public function level()
    {
        $user = auth()->user();
        $config = config('levels.levels.' . $user->level);
        return view('level', [
            'user' => $user,
            'config' => $config,
        ]);
    }

}
