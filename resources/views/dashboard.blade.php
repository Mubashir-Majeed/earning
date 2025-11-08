@extends('layouts.user')

@section('title', 'Dashboard - Earn Quest')
@section('page-title', 'Dashboard')
@section('quick-videos', number_format($stats['total_videos_watched']))
@section('quick-earnings', '$' . number_format($stats['total_earnings'], 2))
@section('quick-points', number_format($stats['total_points']))

@section('content')
    <div class="space-y-8">
        <section class="relative overflow-hidden rounded-3xl p-8 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 shadow-2xl text-white">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');"></div>
            </div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="fas fa-user-circle text-4xl text-white drop-shadow-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-blue-100">Welcome back</p>
                        <h2 class="text-4xl font-black leading-tight">{{ $user->name }}</h2>
                        <p class="text-lg text-blue-100 max-w-2xl mt-3">Here’s your earning overview and today’s key tasks. Keep watching videos, completing referrals, and finish the checklist to unlock withdrawals.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full sm:w-auto">
                    <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-4">
                        <p class="text-xs uppercase tracking-widest text-blue-100">Videos Watched</p>
                        <p class="text-2xl font-bold">{{ number_format($stats['total_videos_watched']) }}</p>
                    </div>
                    <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-4">
                        <p class="text-xs uppercase tracking-widest text-blue-100">Total Earnings</p>
                        <p class="text-2xl font-bold">${{ number_format($stats['total_earnings'], 2) }}</p>
                    </div>
                    <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-4">
                        <p class="text-xs uppercase tracking-widest text-blue-100">Points</p>
                        <p class="text-2xl font-bold">{{ number_format($stats['total_points']) }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Videos Watched</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_videos_watched']) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                        <i class="fas fa-play text-lg"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-green-600 flex items-center gap-1"><i class="fas fa-arrow-trend-up"></i><span>+12% from last week</span></p>
            </div>
            <div class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Total Earnings</p>
                        <p class="text-3xl font-bold text-slate-900">${{ number_format($stats['total_earnings'], 2) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-lg"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-blue-600 flex items-center gap-1"><i class="fas fa-arrow-trend-up"></i><span>+8% from last week</span></p>
            </div>
            <div class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Points Earned</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_points']) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-star text-lg"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-purple-600 flex items-center gap-1"><i class="fas fa-arrow-trend-up"></i><span>+15% from last week</span></p>
            </div>
            <div class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Today’s Earnings</p>
                        <p class="text-3xl font-bold text-slate-900">${{ number_format($stats['today_earnings'], 2) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <i class="fas fa-calendar-day text-lg"></i>
                    </div>
                </div>
                <p class="mt-4 text-sm text-orange-500 flex items-center gap-1"><i class="fas fa-arrow-trend-up"></i><span>+5% from yesterday</span></p>
            </div>
        </section>

        @if(!$user->has_deposited)
            <section class="relative overflow-hidden rounded-3xl p-8 bg-gradient-to-r from-rose-500 via-pink-500 to-rose-600 text-white shadow-2xl">
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,<svg width="44" height="44" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.25"><circle cx="22" cy="22" r="2"/></g></svg>');"></div>
                <div class="relative z-10 space-y-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <h3 class="text-3xl font-black">Deposit Required to Activate Tasks</h3>
                        <span class="inline-flex items-center px-4 py-1 rounded-full bg-white/20 text-sm font-semibold uppercase tracking-widest">Action Needed</span>
                    </div>
                    <p class="text-lg text-rose-100 max-w-4xl">Activate your Earn Quest account by choosing a starter package ($35, $50, or $100). Deposits unlock daily tasks, referral rewards, and withdrawal eligibility.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('deposit') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-rose-600 font-extrabold rounded-2xl shadow-xl hover:bg-rose-50 transition-transform hover:scale-[1.02]">
                            <i class="fas fa-credit-card"></i> Choose Package
                        </a>
                        <a href="{{ route('referrals') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/20 text-white font-semibold rounded-2xl border border-white/30 hover:bg-white/25 transition">
                            <i class="fas fa-users"></i> View Referral Rules
                        </a>
                    </div>
                </div>
            </section>
        @else
            <section class="relative overflow-hidden rounded-3xl p-8 bg-gradient-to-r from-emerald-500 via-green-500 to-emerald-600 text-white shadow-2xl">
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-start gap-5">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur flex items-center justify-center">
                            <i class="fas fa-trophy text-4xl"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black">Great Job Today!</h3>
                            <p class="text-lg text-emerald-100">You’re on the {{ $package['name'] ?? 'Earn Quest' }} package @if($package) ( ${{ number_format($package['deposit_amount'], 2) }} deposit • ${{ number_format($package['withdrawal_cap'], 2) }} cap ) @endif.</p>
                            <p class="text-lg text-white/80 mt-2">Today’s earnings: ${{ number_format($stats['today_earnings'], 2) }}. Keep watching videos to earn even more!</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @php
            $packageCatalog = config('investment.packages');
            $primaryReferralRule = collect($referralProgress)->firstWhere('is_alternative', false);
            $alternativeReferralRules = collect($referralProgress)->filter(fn ($rule) => $rule['is_alternative']);
            $hasPendingDeposit = !empty($user->pending_deposit_amount);
            $referralMet = $user->meetsReferralRequirementForWithdrawal();
        @endphp

        <section class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Compliance Checklist</h3>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50/60">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $user->has_deposited ? 'bg-emerald-100 text-emerald-600' : ($hasPendingDeposit ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600') }}">
                        <i class="fas {{ $user->has_deposited ? 'fa-check' : ($hasPendingDeposit ? 'fa-clock' : 'fa-xmark') }} text-xs"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Package Deposit</p>
                        <p class="text-xs text-slate-500">
                            @if($user->has_deposited && $package)
                                {{ $package['name'] }} • ${{ number_format($package['deposit_amount'], 2) }} deposited
                            @elseif($hasPendingDeposit)
                                Pending review • ${{ number_format($user->pending_deposit_amount, 2) }}
                            @else
                                Choose $35, $50, or $100 package
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50/60">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $user->hasBoundWallet() ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        <i class="fas {{ $user->hasBoundWallet() ? 'fa-check' : 'fa-unlock' }} text-xs"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">BEP20 Wallet Binding</p>
                        <p class="text-xs text-slate-500">{{ $user->hasBoundWallet() ? \Illuminate\Support\Str::limit($user->bep20_address, 16, '...') : 'Bind your BEP20 wallet' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50/60">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $user->hasSubscribedChannel() ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                        <i class="fas {{ $user->hasSubscribedChannel() ? 'fa-check' : 'fa-play' }} text-xs"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Channel Subscription</p>
                        <p class="text-xs text-slate-500">{{ $user->hasSubscribedChannel() ? 'Confirmed' : 'Subscribe & confirm from Task Center' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50/60">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $referralMet ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                        <i class="fas {{ $referralMet ? 'fa-check' : 'fa-user-group' }} text-xs"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Referral Progress</p>
                        <p class="text-xs text-slate-500">
                            @if($primaryReferralRule)
                                {{ $primaryReferralRule['current'] }}/{{ $primaryReferralRule['required'] }} {{ $packageCatalog[$primaryReferralRule['package']]['name'] ?? strtoupper($primaryReferralRule['package']) }} referrals
                            @else
                                {{ $user->referrals()->count() }} referrals recorded
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50/60">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center {{ $user->withinMonthlyWithdrawalQuota() ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        <i class="fas {{ $user->withinMonthlyWithdrawalQuota() ? 'fa-check' : 'fa-circle-exclamation' }} text-xs"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Monthly Withdrawal Quota</p>
                        <p class="text-xs text-slate-500">{{ $user->monthly_withdrawals_count }}/{{ $user->withdrawalMonthlyLimit() }} used</p>
                    </div>
                </div>
            </div>
            @if($alternativeReferralRules->isNotEmpty())
                <div class="mt-5 bg-blue-50/70 border border-blue-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-widest mb-2">Alternative Referral Paths</p>
                    <ul class="text-xs text-blue-600 space-y-1">
                        @foreach($alternativeReferralRules as $rule)
                            <li>• {{ $rule['current'] }}/{{ $rule['required'] }} {{ $packageCatalog[$rule['package']]['name'] ?? strtoupper($rule['package']) }} referrals ({{ $rule['description'] }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>

        <section class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Task Center</h3>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 border border-slate-100 rounded-xl bg-white/70">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Subscribe to the Earn Quest channel</p>
                        <p class="text-xs text-slate-500">Stay updated with announcements and tutorials. Subscription is required before your first withdrawal.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="https://www.youtube.com/@earnquest" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-600 transition">
                            <i class="fab fa-youtube"></i> Open Channel
                        </a>
                        <form method="POST" action="{{ route('tasks.channel-subscribe') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold {{ $user->hasSubscribedChannel() ? 'bg-emerald-100 text-emerald-700 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 transition' }}" {{ $user->hasSubscribedChannel() ? 'disabled' : '' }}>
                                <i class="fas {{ $user->hasSubscribedChannel() ? 'fa-check' : 'fa-clipboard-check' }}"></i>
                                {{ $user->hasSubscribedChannel() ? 'Subscription Confirmed' : 'Confirm Subscription' }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 border border-slate-100 rounded-xl bg-white/70">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Bind your BEP20 withdrawal wallet</p>
                        <p class="text-xs text-slate-500">Withdrawals are only processed to your bound BEP20 address. Update it once from your profile settings.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold {{ $user->hasBoundWallet() ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            <i class="fas {{ $user->hasBoundWallet() ? 'fa-lock' : 'fa-unlock' }}"></i>
                            {{ $user->hasBoundWallet() ? 'Wallet Bound' : 'Binding Required' }}
                        </span>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg shadow hover:bg-slate-800 transition">
                            <i class="fas fa-user-cog"></i> Update Profile
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900">Quick Actions</h3>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('videos.index') }}" class="group bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-transform">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold mb-1">Watch Videos</h4>
                                    <p class="text-sm text-blue-100">Start earning by watching tasks</p>
                                </div>
                                <i class="fas fa-play-circle text-3xl group-hover:scale-110 transition-transform"></i>
                            </div>
                        </a>
                        <a href="{{ route('withdrawal.history') }}" class="group bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-transform">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold mb-1">Withdrawal History</h4>
                                    <p class="text-sm text-emerald-100">Track your withdrawal requests</p>
                                </div>
                                <i class="fas fa-clock-rotate-left text-3xl group-hover:scale-110 transition-transform"></i>
                            </div>
                        </a>
                        <a href="{{ route('earnings') }}" class="group bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-transform">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold mb-1">View Earnings</h4>
                                    <p class="text-sm text-purple-100">Review your earning history</p>
                                </div>
                                <i class="fas fa-chart-line text-3xl group-hover:scale-110 transition-transform"></i>
                            </div>
                        </a>
                        <a href="{{ route('referrals') }}" class="group bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-transform">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold mb-1">Refer & Earn</h4>
                                    <p class="text-sm text-orange-100">Share your referral link</p>
                                </div>
                                <i class="fas fa-share-nodes text-3xl group-hover:scale-110 transition-transform"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white/90 backdrop-blur rounded-2xl border border-white/40 shadow-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900">Recent Activity</h3>
                        <div class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="fas fa-play text-sm"></i>
                            </span>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-900">Watched Video</p>
                                <p class="text-xs text-slate-500">2 minutes ago</p>
                            </div>
                            <span class="text-sm font-bold text-emerald-600">+$0.50</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i class="fas fa-star text-sm"></i>
                            </span>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-900">Earned Points</p>
                                <p class="text-xs text-slate-500">5 minutes ago</p>
                            </div>
                            <span class="text-sm font-bold text-blue-600">+10 pts</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="w-9 h-9 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i class="fas fa-gift text-sm"></i>
                            </span>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-900">Daily Bonus</p>
                                <p class="text-xs text-slate-500">1 hour ago</p>
                            </div>
                            <span class="text-sm font-bold text-purple-600">+$2.00</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('level') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View my level →</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection