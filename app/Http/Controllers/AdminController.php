<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Video;
use App\Models\Withdrawal;
use App\Models\Deposit;
use App\Models\UserEarning;
use App\Traits\CreatesNotifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use CreatesNotifications;
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
        
        $userQuery = User::withoutAdmins();
        
        $stats = [
            'total_users' => (clone $userQuery)->count(),
            'active_users' => (clone $userQuery)->where('is_active', true)->count(),
            'total_videos' => Video::count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')
                ->whereHas('user', fn ($q) => $q->withoutAdmins())
                ->count(),
            'total_earnings' => UserEarning::whereHas('user', fn ($q) => $q->withoutAdmins())->sum('dollar_value'),
            'total_deposits' => Deposit::where('status', 'completed')
                ->whereHas('user', fn ($q) => $q->withoutAdmins())
                ->sum('amount'),
            'today_revenue' => Deposit::where('status', 'completed')
                ->whereHas('user', fn ($q) => $q->withoutAdmins())
                ->whereDate('created_at', now()->toDateString())
                ->sum('amount'),
        ];

        $recentUsers = (clone $userQuery)->latest()->limit(5)->get();
        $recentWithdrawals = Withdrawal::with('user')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentWithdrawals'));
    }

    public function users(Request $request)
    {
        $this->checkAdminRole();
        
        $query = User::withoutAdmins()
            ->with('roles')
            ->withSum([
                'deposits as total_deposited_amount' => function ($query) {
                    $query->where('status', 'completed');
                },
            ], 'amount')
            ->withSum([
                'withdrawals as total_withdrawn_amount' => function ($query) {
                    $query->where('status', 'completed');
                },
            ], 'amount');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Package filter
        if ($request->filled('package')) {
            $query->where('investment_package', $request->package);
        }
        
        // Deposit status filter
        if ($request->filled('deposit_status')) {
            if ($request->deposit_status === 'deposited') {
                $query->where('has_deposited', true);
            } elseif ($request->deposit_status === 'no_deposit') {
                $query->where('has_deposited', false);
            }
        }
        
        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $users = $query->paginate(20)->withQueryString();
        $packageCatalog = config('investment.packages', []);
        
        return view('admin.users', compact('users', 'packageCatalog'));
    }

    public function showUser(User $user)
    {
        $this->checkAdminRole();

        if ($user->hasRole('admin')) {
            abort(404);
        }

        $user->load([
            'deposits' => function ($query) {
                $query->orderByDesc('created_at');
            },
            'withdrawals' => function ($query) {
                $query->orderByDesc('created_at');
            },
            'referrals' => function ($query) {
                $query->where('has_deposited', true)
                    ->with([
                        'deposits' => function ($q) {
                            $q->where('status', 'completed');
                        },
                        'withdrawals' => function ($q) {
                            $q->where('status', 'completed');
                        },
                    ]);
            },
        ]);

        $packages = config('investment.packages', []);
        $referralCounts = $user->referralCountsByPackage();
        $referralProgress = $user->referralProgress();

        $financials = [
            'total_deposited' => $user->deposits->where('status', 'completed')->sum('amount'),
            'total_withdrawn' => $user->withdrawals->where('status', 'completed')->sum('amount'),
            'current_balance' => $user->balance,
        ];

        $referralDetails = $user->referrals->map(function ($referral) use ($packages) {
            $package = $referral->investment_package ? ($packages[$referral->investment_package] ?? null) : null;

            return [
                'name' => $referral->name,
                'email' => $referral->email,
                'package_code' => $referral->investment_package,
                'package_name' => $package['name'] ?? '—',
                'package_deposit' => $package['deposit_amount'] ?? $referral->initial_deposit_amount,
                'total_deposited' => $referral->deposits->sum('amount'),
                'total_withdrawn' => $referral->withdrawals->sum('amount'),
                'balance' => $referral->balance,
                'joined_at' => $referral->created_at,
            ];
        });

        return view('admin.user-show', compact(
            'user',
            'packages',
            'referralCounts',
            'referralProgress',
            'financials',
            'referralDetails'
        ));
    }

    public function toggleUserActive(User $user)
    {
        $this->checkAdminRole();
        if ($user->hasRole('admin')) {
            abort(403, 'Cannot modify admin account');
        }
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    public function destroyUser(User $user)
    {
        $this->checkAdminRole();
        if ($user->hasRole('admin')) {
            abort(403, 'Cannot delete admin account');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function videos(Request $request)
    {
        $this->checkAdminRole();
        
        $query = Video::query();

        // Search filter - title or category
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $videos = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Get all unique categories for the filter dropdown
        $categories = Video::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return view('admin.videos', compact('videos', 'categories'));
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
        
        // Custom validation for supported video URLs
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    // Check if it's a supported video URL
                    $source = Video::detectVideoSource($value);
                    if (!$source) {
                        $fail('The video URL must be a valid YouTube (watch/shorts) or TikTok link.');
                    }
                },
            ],
            'youtube_id' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'thumbnail_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'duration' => 'required|integer|min:1',
            'dollar_value' => 'required|numeric|min:0.01',
            'dollar_value_starter' => 'nullable|numeric|min:0.01',
            'dollar_value_growth' => 'nullable|numeric|min:0.01',
            'dollar_value_pro' => 'nullable|numeric|min:0.01',
            'assigned_date' => 'nullable|date',
            'max_watches_per_day' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated = $request->only([
            'title', 'description', 'youtube_url', 'youtube_id', 'category',
            'thumbnail_url', 'duration', 'dollar_value',
            'dollar_value_starter', 'dollar_value_growth', 'dollar_value_pro',
            'assigned_date',
            'max_watches_per_day', 'is_active'
        ]);

        // Detect platform & extract ID from URL
        $source = Video::detectVideoSource($validated['youtube_url']);
        if ($source) {
            $validated['platform'] = $source['platform'];
        if (empty($validated['youtube_id'])) {
                $validated['youtube_id'] = $source['video_id'];
            }
        }

        $validated['dollar_value_starter'] = $validated['dollar_value_starter'] ?? $validated['dollar_value'];
        $validated['dollar_value_growth'] = $validated['dollar_value_growth'] ?? $validated['dollar_value'];
        $validated['dollar_value_pro'] = $validated['dollar_value_pro'] ?? $validated['dollar_value'];

        // Validate that youtube_id was successfully extracted
        if (empty($validated['youtube_id'])) {
            return back()
                ->withInput()
                ->withErrors(['youtube_url' => 'Could not extract a video ID from the provided URL. Please check the URL format.']);
        }

        // If a file thumbnail is uploaded, store it and override thumbnail_url
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail_url'] = asset('storage/' . $path);
        }

        // Set default assigned_date to today if not provided
        if (empty($validated['assigned_date'])) {
            $validated['assigned_date'] = now()->toDateString();
        }

        // Set default max_watches_per_day if not provided
        if (empty($validated['max_watches_per_day'])) {
            $validated['max_watches_per_day'] = 1;
        }

        // Ensure is_active is set
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $video = Video::create($validated);
        return redirect()->route('admin.videos')->with('success', 'Video added successfully.');
    }

    public function editVideo(Video $video)
    {
        $this->checkAdminRole();
        return view('admin.video-edit', compact('video'));
    }

    public function updateVideo(Request $request, Video $video)
    {
        $this->checkAdminRole();
        
        // Custom validation for supported video URLs
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    // Check if it's a supported video URL
                    $source = Video::detectVideoSource($value);
                    if (!$source) {
                        $fail('The video URL must be a valid YouTube (watch/shorts) or TikTok link.');
                    }
                },
            ],
            'youtube_id' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'thumbnail_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'duration' => 'required|integer|min:1',
            'dollar_value' => 'required|numeric|min:0.01',
            'dollar_value_starter' => 'nullable|numeric|min:0.01',
            'dollar_value_growth' => 'nullable|numeric|min:0.01',
            'dollar_value_pro' => 'nullable|numeric|min:0.01',
            'assigned_date' => 'nullable|date',
            'max_watches_per_day' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated = $request->only([
            'title', 'description', 'youtube_url', 'youtube_id', 'category',
            'thumbnail_url', 'duration', 'dollar_value',
            'dollar_value_starter', 'dollar_value_growth', 'dollar_value_pro',
            'assigned_date',
            'max_watches_per_day'
        ]);

        // Detect platform & extract ID from URL
        $source = Video::detectVideoSource($validated['youtube_url']);
        if ($source) {
            $validated['platform'] = $source['platform'];
            if (empty($validated['youtube_id'])) {
                $validated['youtube_id'] = $source['video_id'];
            }
        }

        $validated['dollar_value_starter'] = $validated['dollar_value_starter'] ?? $validated['dollar_value'];
        $validated['dollar_value_growth'] = $validated['dollar_value_growth'] ?? $validated['dollar_value'];
        $validated['dollar_value_pro'] = $validated['dollar_value_pro'] ?? $validated['dollar_value'];

        // Validate that youtube_id was successfully extracted
        if (empty($validated['youtube_id'])) {
            return back()
                ->withInput()
                ->withErrors(['youtube_url' => 'Could not extract a video ID from the provided URL. Please check the URL format.']);
        }

        // If a file thumbnail is uploaded, store it and override thumbnail_url
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail_url'] = asset('storage/' . $path);
        }

        // Set default max_watches_per_day if not provided
        if (empty($validated['max_watches_per_day'])) {
            $validated['max_watches_per_day'] = 1;
        }

        // Handle is_active checkbox (checkbox sends value only if checked)
        $validated['is_active'] = $request->has('is_active') && $request->is_active == '1';

        $video->update($validated);
        return redirect()->route('admin.videos')->with('success', 'Video updated successfully.');
    }

    public function withdrawals()
    {
        $this->checkAdminRole();
        
        $withdrawals = Withdrawal::with('user')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function deposits(Request $request)
    {
        $this->checkAdminRole();

        $query = Deposit::with('user')
            ->whereHas('user', fn ($q) => $q->withoutAdmins());

        // Search filter - user name, email, or transaction ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search by user name or email
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                // Search by transaction ID (payment_id or in notes)
                ->orWhere('payment_id', 'like', "%{$search}%")
                ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Amount range filter
        if ($request->filled('amount_range')) {
            $amount = (float) $request->amount_range;
            $query->where('amount', $amount);
        }

        $deposits = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $packages = config('investment.packages', []);

        return view('admin.deposits', compact('deposits', 'packages'));
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

            $packageCode = $this->resolvePackageCode($deposit->package_code, $deposit->amount);

            $deposit->user->update([
                'initial_deposit_amount' => $deposit->amount,
                'investment_package' => $packageCode,
                'pending_deposit_amount' => null,
                'pending_package_code' => null,
                'unwithdrawable_balance_min' => $deposit->amount,
                'bep20_address' => $deposit->user->bep20_address ?? $deposit->payment_details,
                'wallet_bound_at' => $deposit->user->wallet_bound_at ?? now(),
            ]);

            // Award referral bonus ONLY if user has a referrer, deposit is Pro package ($100), and hasn't been awarded yet
            if ($deposit->user->referrer_id) {
                // Check if this is a Pro package deposit ($100 or pro_100)
                $isProPackage = ($packageCode === 'pro_100') || ((float) $deposit->amount === 100.00);
                
                if ($isProPackage) {
                    $referrer = $deposit->user->referrer;
                    
                    // Check if referral bonus has already been awarded for this user
                    $alreadyAwarded = \App\Models\UserEarning::where('user_id', $referrer->id)
                        ->where('type', 'referral')
                        ->where('description', 'like', "%{$deposit->user->name}%")
                        ->exists();
                    
                    if (!$alreadyAwarded) {
                        // Award $5 to referrer only when referred user deposits Pro package
                        $referrer->increment('balance', 5.00);
                        
                        // Increment referrer's referral count (only if not already counted)
                        $referrer->increment('referrals_count');
                        
                        // Create earning record for referrer
                        \App\Models\UserEarning::create([
                            'user_id' => $referrer->id,
                            'dollar_value' => 5.00,
                            'type' => 'referral',
                            'description' => "Referral bonus for {$deposit->user->name} (Pro package deposit)",
                            'earned_date' => now()->toDateString(),
                        ]);
                        
                        // Create notification for referrer
                        $packageName = config("investment.packages.{$packageCode}.name", "Package");
                        self::notifyReferral($referrer, $deposit->user->name, $packageName);
                    } else {
                        // Sync the count to ensure accuracy (in case of data inconsistency)
                        $actualCount = $referrer->actual_referrals_count;
                        if ($referrer->referrals_count != $actualCount) {
                            $referrer->update(['referrals_count' => $actualCount]);
                        }
                    }
                }
            }
            
            // Create notification for deposit approval
            $packageName = config("investment.packages.{$packageCode}.name", "Package");
            self::notifyDepositApproved($deposit->user, $deposit->amount, $packageName);
        });

        return redirect()->route('admin.deposits')->with('success', 'Deposit completed and user balance credited.');
    }

    public function failDeposit(Request $request, Deposit $deposit)
    {
        $this->checkAdminRole();

        $request->validate([
            'failure_reason' => 'required|string|max:500',
        ]);

        if ($deposit->status !== 'failed') {
            DB::transaction(function () use ($deposit, $request) {
            $deposit->update([
                'status' => 'failed',
                    'notes' => $request->failure_reason,
                ]);

                $user = $deposit->user;
                if ($user) {
                    $shouldClearPending = $user->pending_package_code === $deposit->package_code
                        || (float) $user->pending_deposit_amount === (float) $deposit->amount;

                    if ($shouldClearPending) {
                        $user->update([
                            'pending_deposit_amount' => null,
                            'pending_package_code' => null,
                        ]);
                    }
                }
            });
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
        
        // Create notification for withdrawal approval
        self::notifyWithdrawalApproved($withdrawal->user, $withdrawal->amount);

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

    private function resolvePackageCode(?string $packageCode, float $amount): ?string
    {
        if ($packageCode) {
            return $packageCode;
        }

        $packages = config('investment.packages', []);
        foreach ($packages as $code => $package) {
            if ((float) $package['deposit_amount'] === (float) $amount) {
                return $code;
            }
        }

        return null;
    }

    public function analytics()
    {
        $this->checkAdminRole();
        
        // Get analytics data
        $userQuery = User::withoutAdmins();
        $totalUsers = (clone $userQuery)->count();
        $activeUsers = (clone $userQuery)->where('is_active', true)->count();
        $totalVideos = Video::count();
        $activeVideos = Video::where('is_active', true)->count();
        $totalDeposits = Deposit::where('status', 'completed')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->sum('amount');
        $totalWithdrawals = Withdrawal::where('status', 'completed')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->sum('amount');
        
        // Monthly data for charts
        $monthlyUsers = User::withoutAdmins()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $monthlyRevenue = Deposit::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $monthlyWithdrawals = Withdrawal::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->whereHas('user', fn ($q) => $q->withoutAdmins())
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyUsersChart = $monthlyUsers->map(function ($row) {
            return [
                'label' => Carbon::createFromFormat('Y-m', $row->month)->format('M Y'),
                'value' => (int) $row->count,
            ];
        })->values();

        $chartMonths = $monthlyRevenue->pluck('month')
            ->merge($monthlyWithdrawals->pluck('month'))
            ->unique()
            ->sort()
            ->values();

        $monthlyRevenueChart = $chartMonths->map(function ($month) use ($monthlyRevenue, $monthlyWithdrawals) {
            $revenue = optional($monthlyRevenue->firstWhere('month', $month))->total ?? 0;
            $withdrawal = optional($monthlyWithdrawals->firstWhere('month', $month))->total ?? 0;

            return [
                'label' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                'revenue' => (float) $revenue,
                'withdrawals' => (float) $withdrawal,
            ];
        });

        return view('admin.analytics', compact(
            'totalUsers', 'activeUsers', 'totalVideos', 'activeVideos',
            'totalDeposits', 'totalWithdrawals', 'pendingWithdrawals',
            'monthlyUsersChart', 'monthlyRevenueChart'
        ));
    }

    public function settings()
    {
        $this->checkAdminRole();
        
        $defaultPackages = config('investment.base_packages', config('investment.packages', []));

        $settings = [
            'site_name' => \App\Models\Setting::getValue('site_name', config('app.name', 'Earn Quest')),
            'site_email' => \App\Models\Setting::getValue('site_email', config('mail.from.address', 'admin@earnquest.com')),
            'min_withdrawal' => \App\Models\Setting::getValue('min_withdrawal', config('platform.min_withdrawal', 10)),
            'withdrawal_fee_percent' => \App\Models\Setting::getValue('withdrawal_fee_percent', config('platform.withdrawal_fee_percent', 5)),
            'referral_bonus' => \App\Models\Setting::getValue('referral_bonus', config('platform.referral_bonus', 5)),
            'platform_wallet_address' => \App\Models\Setting::getValue('platform_wallet_address', config('platform.wallet_address')),
            'youtube_channel_url' => \App\Models\Setting::getValue('youtube_channel_url', 'https://www.youtube.com/@earnquest'),
            'packages' => array_replace_recursive(
                $defaultPackages,
                (array) \App\Models\Setting::getValue('packages', [])
            ),
        ];

        return view('admin.settings', compact('settings', 'defaultPackages'));
    }

    public function updateSettings(Request $request)
    {
        $this->checkAdminRole();
        
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'min_withdrawal' => 'required|numeric|min:1',
            'withdrawal_fee_percent' => 'required|numeric|min:0|max:100',
            'referral_bonus' => 'required|numeric|min:0',
            'platform_wallet_address' => ['nullable', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'youtube_channel_url' => 'nullable|url|max:500',
            'packages' => 'required|array',
        ]);

        $defaultPackages = config('investment.base_packages', []);
        $packagesInput = $request->input('packages', []);

        foreach ($defaultPackages as $code => $config) {
            $request->validate([
                "packages.$code.deposit_amount" => 'nullable|numeric|min:1',
                "packages.$code.withdrawal_cap" => 'nullable|numeric|min:0',
        ]);
        }

        $normalizedPackages = [];
        foreach ($defaultPackages as $code => $config) {
            $normalizedPackages[$code] = [
                'name' => $config['name'],
                'description' => $config['description'],
                'deposit_amount' => isset($packagesInput[$code]['deposit_amount'])
                    ? (float) $packagesInput[$code]['deposit_amount']
                    : (float) $config['deposit_amount'],
                'withdrawal_cap' => isset($packagesInput[$code]['withdrawal_cap'])
                    ? (float) $packagesInput[$code]['withdrawal_cap']
                    : (float) $config['withdrawal_cap'],
            ];
        }

        \App\Models\Setting::setValue('site_name', $validated['site_name']);
        \App\Models\Setting::setValue('site_email', $validated['site_email']);
        \App\Models\Setting::setValue('min_withdrawal', (float) $validated['min_withdrawal']);
        \App\Models\Setting::setValue('withdrawal_fee_percent', (float) $validated['withdrawal_fee_percent']);
        \App\Models\Setting::setValue('referral_bonus', (float) $validated['referral_bonus']);
        \App\Models\Setting::setValue('platform_wallet_address', $validated['platform_wallet_address']);
        \App\Models\Setting::setValue('youtube_channel_url', $validated['youtube_channel_url'] ?? 'https://www.youtube.com/@earnquest');
        \App\Models\Setting::setValue('packages', $normalizedPackages);

        \App\Models\Setting::apply($defaultPackages);
        
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    public function referrals()
    {
        $this->checkAdminRole();

        $referrerBaseQuery = User::withoutAdmins()->whereHas('referrals', function ($query) {
            $query->withoutAdmins();
        });

        $referrers = (clone $referrerBaseQuery)
            ->with(['referrals' => function ($query) {
                $query->withoutAdmins()
                    ->with(['deposits' => function ($q) {
                        $q->where('status', 'completed');
                    }, 'withdrawals' => function ($q) {
                        $q->where('status', 'completed');
                    }]);
            }])
            ->orderByDesc('created_at')
            ->paginate(10);

        $totalReferrers = (clone $referrerBaseQuery)->count();
        $totalReferrals = User::withoutAdmins()->whereNotNull('referrer_id')->count();
        $activeReferrals = User::withoutAdmins()->whereNotNull('referrer_id')->where('has_deposited', true)->count();

        $referralDepositSum = Deposit::where('status', 'completed')
            ->whereHas('user', function ($query) {
                $query->withoutAdmins()->whereNotNull('referrer_id');
            })
            ->sum('amount');

        $referralWithdrawalSum = Withdrawal::where('status', 'completed')
            ->whereHas('user', function ($query) {
                $query->withoutAdmins()->whereNotNull('referrer_id');
            })
            ->sum('amount');

        $packages = config('investment.packages', []);

        $overview = [
            'total_referrers' => $totalReferrers,
            'total_referrals' => $totalReferrals,
            'active_referrals' => $activeReferrals,
            'total_referral_deposits' => $referralDepositSum,
            'total_referral_withdrawals' => $referralWithdrawalSum,
        ];

        return view('admin.referrals', compact('referrers', 'overview', 'packages'));
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
            
            // Create notification for withdrawal rejection
            self::notifyWithdrawalRejected($withdrawal->user, $withdrawal->amount, $request->admin_notes);
        });

        return redirect()->route('admin.withdrawals')->with('success', 'Withdrawal rejected and amount refunded to user.');
    }

    public function withdrawalDetails(Withdrawal $withdrawal)
    {
        $this->checkAdminRole();
        
        return response()->json([
            'user_name' => $withdrawal->user->name,
            'user_email' => $withdrawal->user->email,
            'amount' => $withdrawal->amount,
            'fee_amount' => $withdrawal->fee_amount,
            'net_amount' => $withdrawal->net_amount,
            'status' => ucfirst($withdrawal->status),
            'method' => strtoupper($withdrawal->withdrawal_method ?? 'BEP20'),
            'wallet_address' => $withdrawal->withdrawal_details,
            'requested_at' => $withdrawal->requested_at ? \Carbon\Carbon::parse($withdrawal->requested_at)->format('M d, Y H:i') : null,
            'processed_at' => $withdrawal->processed_at ? \Carbon\Carbon::parse($withdrawal->processed_at)->format('M d, Y H:i') : null,
            'completed_at' => $withdrawal->completed_at ? \Carbon\Carbon::parse($withdrawal->completed_at)->format('M d, Y H:i') : null,
            'admin_notes' => $withdrawal->admin_notes,
        ]);
    }

    public function addWithdrawalNote(Request $request, Withdrawal $withdrawal)
    {
        $this->checkAdminRole();
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $withdrawal->update([
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.withdrawals')->with('success', 'Admin note updated successfully.');
    }
}