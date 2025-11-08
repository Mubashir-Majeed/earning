<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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

        $package = $user->investment_package ? config('investment.packages.' . $user->investment_package) : null;
        $referralProgress = $user->referralProgress();
        $referralCounts = $user->referralCountsByPackage();

        return view('dashboard', compact(
            'user',
            'stats',
            'todayTasks',
            'recentEarnings',
            'package',
            'referralProgress',
            'referralCounts'
        ));
    }

    public function deposit()
    {
        $user = auth()->user();
        
        if ($user->has_deposited) {
            return redirect()->route('dashboard')->with('info', 'You have already made your initial deposit.');
        }

        $packages = config('investment.packages', []);
        return view('deposit', compact('user', 'packages'));
    }

    public function withdrawal()
    {
        $user = auth()->user();
        $stats = $this->videoEarningService->getUserStats($user);
        $package = $user->investment_package ? config('investment.packages.' . $user->investment_package) : null;
        $referralProgress = $user->referralProgress();
        return view('withdrawal', compact('user', 'stats', 'package', 'referralProgress'));
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
        $packages = config('investment.packages', []);
        $referralCounts = $user->referralCountsByPackage();
        $referralProgress = $user->referralProgress();
        return view('referrals', compact('user', 'referrals', 'referralLink', 'packages', 'referralCounts', 'referralProgress'));
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

    public function confirmChannelSubscription(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasSubscribedChannel()) {
            return back()->with('info', 'Channel subscription already confirmed.');
        }

        $user->update([
            'channel_subscribed_at' => now(),
        ]);

        return back()->with('success', 'Thanks for subscribing to our channel!');
    }

}
