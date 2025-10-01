@extends('layouts.user')

@section('title', 'My Level - VideoEarn')
@section('page-title', 'My Level')

@section('content')
        <h1 class="text-2xl font-bold mb-6">My Level</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-600">Current Level</p>
                <p class="text-3xl font-bold capitalize">{{ str_replace('_',' ', $user->level) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-600">Daily Videos</p>
                <p class="text-3xl font-bold">{{ $config['daily_videos'] ?? 5 }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-600">Withdrawals / Month</p>
                <p class="text-3xl font-bold">{{ $config['withdrawals_per_month'] ?? 1 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Requirements</h2>
            <ul class="space-y-2 text-sm">
                <li>Required deposit: ${{ $config['required_deposit'] ?? 100 }}</li>
                @if($user->level !== 'level_1')
                    <li>Referral requirement (deposit $100): {{ $config['required_referrals_if_new_deposit_100'] ?? '-' }}</li>
                    <li>Referral requirement (deposit $50): {{ $config['required_referrals_if_new_deposit_50'] ?? '-' }}</li>
                @else
                    <li>Referral requirement for withdrawal: {{ $config['required_referrals_for_withdrawal'] ?? 1 }}</li>
                @endif
                <li>Un-withdrawable minimum balance: ${{ $config['unwithdrawable_balance_min'] ?? 50 }}</li>
                <li>Withdrawal fee: {{ $config['withdrawal_fee_percent'] ?? 5 }}%</li>
                @if(($config['locked'] ?? false))
                    <li class="text-red-600 font-semibold">Level is currently locked.</li>
                @endif
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Progress</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Referrals</p>
                    <p class="text-xl font-bold">{{ $user->referrals()->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Monthly withdrawals used</p>
                    <p class="text-xl font-bold">{{ $user->monthly_withdrawals_count }} / {{ $config['withdrawals_per_month'] ?? 1 }}</p>
                </div>
            </div>
        </div>
@endsection


