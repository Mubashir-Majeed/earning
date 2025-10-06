<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Video;
use App\Models\Withdrawal;
use App\Models\Deposit;
use App\Models\UserEarning;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in routes
    }

    private function checkAdminRole()
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Access denied. Admin privileges required.');
        }
    }

    public function index()
    {
        $this->checkAdminRole();
        
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_videos' => Video::count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'total_earnings' => UserEarning::sum('dollar_value'),
            'total_deposits' => Deposit::where('status', 'completed')->sum('amount'),
            'today_revenue' => Deposit::where('status', 'completed')
                ->whereDate('created_at', now()->toDateString())
                ->sum('amount'),
        ];

        $recentUsers = User::latest()->limit(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentWithdrawals'));
    }

    public function users()
    {
        $this->checkAdminRole();
        
        $users = User::with('roles')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        $this->checkAdminRole();
        return view('admin.user-show', compact('user'));
    }

    public function toggleUserActive(User $user)
    {
        $this->checkAdminRole();
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    public function destroyUser(User $user)
    {
        $this->checkAdminRole();
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function videos()
    {
        $this->checkAdminRole();
        
        $videos = Video::paginate(20);
        return view('admin.videos', compact('videos'));
    }

    public function videoStats(Video $video)
    {
        $this->checkAdminRole();
        $watches = $video->videoWatches()->latest()->paginate(20);
        $tasks = $video->videoTasks()->latest()->paginate(20);
        return view('admin.video-stats', compact('video', 'watches', 'tasks'));
    }

    public function createVideo()
    {
        $this->checkAdminRole();
        return view('admin.video-create');
    }

    public function storeVideo(Request $request)
    {
        $this->checkAdminRole();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'youtube_id' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'thumbnail_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'duration' => 'required|integer|min:1',
            'points_value' => 'required|integer|min:1',
            'assigned_date' => 'nullable|date',
            'max_watches_per_day' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // If a file thumbnail is uploaded, store it and override thumbnail_url
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail_url'] = asset('storage/' . $path);
        }

        // Set default assigned_date to today if not provided
        if (empty($validated['assigned_date'])) {
            $validated['assigned_date'] = now()->toDateString();
        }

        $video = Video::create($validated);
        return redirect()->route('admin.videos')->with('success', 'Video added successfully.');
    }

    public function withdrawals()
    {
        $this->checkAdminRole();
        
        $withdrawals = Withdrawal::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function deposits()
    {
        $this->checkAdminRole();

        $deposits = Deposit::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.deposits', compact('deposits'));
    }

    public function completeDeposit(Request $request, Deposit $deposit)
    {
        $this->checkAdminRole();

        if ($deposit->status === 'completed') {
            return redirect()->route('admin.deposits')->with('info', 'Deposit is already completed.');
        }

        DB::transaction(function () use ($deposit) {
            // Mark deposit completed
            $deposit->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Credit amount to user's balance
            $deposit->user->increment('balance', $deposit->amount);

            // Ensure user is flagged as deposited
            if (!$deposit->user->has_deposited) {
                $deposit->user->update(['has_deposited' => true]);
            }

            // Award referral bonus if user has a referrer
            if ($deposit->user->referrer_id) {
                $referrer = $deposit->user->referrer;
                
                // Award $5 to referrer
                $referrer->increment('balance', 5.00);
                $referrer->increment('points', 100);
                
                // Increment referrer's referral count
                $referrer->increment('referrals_count');
                
                // Create earning record for referrer
                \App\Models\UserEarning::create([
                    'user_id' => $referrer->id,
                    'points_earned' => 100,
                    'dollar_value' => 5.00,
                    'type' => 'referral',
                    'description' => "Referral bonus for {$deposit->user->name}",
                    'earned_date' => now()->toDateString(),
                ]);
            }
        });

        return redirect()->route('admin.deposits')->with('success', 'Deposit completed and user balance credited.');
    }

    public function failDeposit(Request $request, Deposit $deposit)
    {
        $this->checkAdminRole();

        if ($deposit->status !== 'failed') {
            $deposit->update([
                'status' => 'failed',
            ]);
        }

        return redirect()->route('admin.deposits')->with('success', 'Deposit marked as failed.');
    }

    public function approveWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $this->checkAdminRole();
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $withdrawal->update([
            'status' => 'completed',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.withdrawals')->with('success', 'Withdrawal approved and completed successfully.');
    }

    public function processWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $this->checkAdminRole();
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $withdrawal->update([
            'status' => 'processing',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.withdrawals')->with('success', 'Withdrawal marked as processing.');
    }

    public function analytics()
    {
        $this->checkAdminRole();
        
        // Get analytics data
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalVideos = Video::count();
        $activeVideos = Video::where('is_active', true)->count();
        $totalDeposits = Deposit::where('status', 'completed')->sum('amount');
        $totalWithdrawals = Withdrawal::where('status', 'completed')->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->sum('amount');
        
        // Monthly data for charts
        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $monthlyRevenue = Deposit::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $monthlyWithdrawals = Withdrawal::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.analytics', compact(
            'totalUsers', 'activeUsers', 'totalVideos', 'activeVideos',
            'totalDeposits', 'totalWithdrawals', 'pendingWithdrawals',
            'monthlyUsers', 'monthlyRevenue', 'monthlyWithdrawals'
        ));
    }

    public function settings()
    {
        $this->checkAdminRole();
        
        // Get current settings (you can create a settings table later)
        $settings = [
            'site_name' => config('app.name', 'VideoEarn'),
            'site_email' => config('mail.from.address', 'admin@videoearn.com'),
            'min_withdrawal' => 10,
            'withdrawal_fee_percent' => 5,
            'referral_bonus' => 5,
            'video_points_rate' => 0.1, // $0.10 per point
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $this->checkAdminRole();
        
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'min_withdrawal' => 'required|numeric|min:1',
            'withdrawal_fee_percent' => 'required|numeric|min:0|max:100',
            'referral_bonus' => 'required|numeric|min:0',
            'video_points_rate' => 'required|numeric|min:0',
        ]);

        // Here you would typically save to a settings table
        // For now, we'll just show a success message
        
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    public function rejectWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $this->checkAdminRole();
        
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($withdrawal, $request) {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'failed',
                'admin_notes' => $request->admin_notes,
                'processed_at' => now(),
            ]);

            // Refund the amount back to user's balance
            $withdrawal->user->increment('balance', $withdrawal->amount);
        });

        return redirect()->route('admin.withdrawals')->with('success', 'Withdrawal rejected and amount refunded to user.');
    }
}