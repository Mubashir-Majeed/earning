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
        
        // Get latest deposit status for dashboard
        $latestDeposit = $user->deposits()->latest()->first();
        $pendingDeposit = $latestDeposit && $latestDeposit->status === 'pending' ? $latestDeposit : null;

        // Get Level 2 requirements status
        $level2Status = $user->meetsLevel2Requirements();

        return view('dashboard', compact(
            'user',
            'stats',
            'todayTasks',
            'recentEarnings',
            'package',
            'referralProgress',
            'referralCounts',
            'pendingDeposit',
            'level2Status'
        ));
    }

    public function deposit(Request $request)
    {
        $user = auth()->user();
        
        $latestDeposit = $user->deposits()->latest()->with('user')->first();
        $packages = config('investment.packages', []);
        $viewData = [
            'user' => $user,
            'packages' => $packages,
            'platformWallet' => config('platform.wallet_address'),
            'latestDeposit' => $latestDeposit,
        ];

        if ($latestDeposit) {
            if ($latestDeposit->status === 'pending') {
                return view('deposit-pending', array_merge($viewData, [
                    'pendingDeposit' => $latestDeposit,
                ]));
            }

            if ($latestDeposit->status === 'failed' && $request->boolean('retry')) {
                return view('deposit', $viewData);
            }

            if ($latestDeposit->status === 'failed' && !$request->boolean('retry')) {
                return view('deposit-status', array_merge($viewData, [
                    'depositRecord' => $latestDeposit,
                ]));
            }

            if ($request->boolean('new')) {
                return view('deposit', $viewData);
            }

            return view('deposit-status', array_merge($viewData, [
                'depositRecord' => $latestDeposit,
            ]));
        }

        return view('deposit', $viewData);
    }

    public function withdrawal()
    {
        $user = auth()->user();
        $stats = $this->videoEarningService->getUserStats($user);
        $package = $user->investment_package ? config('investment.packages.' . $user->investment_package) : null;
        $referralProgress = $user->referralProgress();
        $minWithdrawal = config('platform.min_withdrawal', 10);
        return view('withdrawal', compact('user', 'stats', 'package', 'referralProgress', 'minWithdrawal'));
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
        // Database-agnostic approach using Carbon
        $earningsData = $user->earnings()
            ->select('earned_date', 'dollar_value')
            ->orderBy('earned_date', 'desc')
            ->get();
        
        // Group by month using Carbon
        $earningsByMonth = $earningsData->groupBy(function($item) {
            return Carbon::parse($item->earned_date)->format('Y-m');
        })->map(function($group, $month) {
            return [
                'month' => $month,
                'total' => (float) $group->sum('dollar_value')
            ];
        });
        
        // Generate last 12 months with zero values for missing months
        $monthlyEarnings = collect();
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        
        for ($i = 0; $i < 12; $i++) {
            $monthKey = $startDate->copy()->addMonths($i)->format('Y-m');
            $monthlyEarnings->push([
                'month' => $monthKey,
                'total' => $earningsByMonth->has($monthKey) 
                    ? (float) $earningsByMonth[$monthKey]['total'] 
                    : 0.0
            ]);
        }

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

    public function upgradeLevel(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->upgradeToLevel2()) {
            return redirect()->route('dashboard')->with('success', 'Congratulations! You have been upgraded to Level 2!');
        }

        return back()->with('error', 'You do not meet all the requirements for Level 2 upgrade yet.');
    }

}
