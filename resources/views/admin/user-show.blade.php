@extends('layouts.admin')

@section('title', 'User Details - Earn Quest')
@section('page-title', 'User Details')

@section('content')
@php
    $packageCatalog = $packages;
    $packageConfig = $user->investment_package ? ($packageCatalog[$user->investment_package] ?? null) : null;
    $progressCollection = collect($referralProgress);
@endphp

<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>{{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->has_deposited ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                    <i class="fas fa-piggy-bank mr-2"></i>{{ $user->has_deposited ? 'Deposit Confirmed' : 'Awaiting Deposit' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->hasBoundWallet() ? 'bg-purple-100 text-purple-700' : 'bg-red-100 text-red-700' }}">
                    <i class="fas fa-link mr-2"></i>{{ $user->hasBoundWallet() ? 'Wallet Bound' : 'Wallet Pending' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Current Balance</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($financials['current_balance'], 2) }}</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Deposited</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($financials['total_deposited'], 2) }}</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Withdrawn</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($financials['total_withdrawn'], 2) }}</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Withdrawable Profit</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($user->withdrawableProfit(), 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Package cap ${{ number_format($user->maxWithdrawableForPackage(), 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Package & Compliance</h3>
            <dl class="space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-600">Package</dt>
                    <dd>{{ $packageConfig['name'] ?? '—' }} @if($packageConfig) ( ${{ number_format($packageConfig['deposit_amount'], 2) }} ) @endif</dd>
                </div>
                @if($user->pending_package_code)
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Pending Deposit</dt>
                        <dd>{{ data_get($packageCatalog, $user->pending_package_code.'.name', 'Package') }} • ${{ number_format($user->pending_deposit_amount ?? 0, 2) }}</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-600">Referral Status</dt>
                    <dd>{{ $user->meetsReferralRequirementForWithdrawal() ? 'Eligible' : 'Needs referrals' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-600">Channel Subscription</dt>
                    <dd>{{ $user->hasSubscribedChannel() ? 'Confirmed' : 'Pending' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-600">BEP20 Wallet</dt>
                    <dd class="font-mono">{{ $user->bep20_address ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-600">Monthly Withdrawals</dt>
                    <dd>{{ $user->monthly_withdrawals_count }} / {{ $user->withdrawalMonthlyLimit() }}</dd>
                </div>
            </dl>

            @if($progressCollection->isNotEmpty())
                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Referral Requirements</p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach($progressCollection as $rule)
                            <li class="flex justify-between">
                                <span>{{ $rule['is_alternative'] ? 'Alternative' : 'Primary' }}: {{ $rule['description'] }}</span>
                                <span class="font-semibold">{{ $rule['current'] }}/{{ $rule['required'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Referrals by Package</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 uppercase text-xs">
                            <th class="pb-2">Package</th>
                            <th class="pb-2">Deposit</th>
                            <th class="pb-2">Completed Referrals</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-900 divide-y divide-gray-100">
                        @foreach($packageCatalog as $code => $config)
                            <tr>
                                <td class="py-2 font-medium">{{ $config['name'] }}</td>
                                <td class="py-2">${{ number_format($config['deposit_amount'], 2) }}</td>
                                <td class="py-2">{{ $referralCounts[$code] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Referral Details</h3>
            <span class="text-xs uppercase tracking-wide text-gray-500">{{ $referralDetails->count() }} referrals</span>
        </div>

        @if($referralDetails->isEmpty())
            <div class="text-center text-sm text-gray-500 py-8">
                No qualifying referrals yet. Encourage the user to share their link.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase text-gray-500">
                            <th class="pb-2">Referral</th>
                            <th class="pb-2">Package</th>
                            <th class="pb-2">Deposited</th>
                            <th class="pb-2">Withdrawn</th>
                            <th class="pb-2">Balance</th>
                            <th class="pb-2">Points</th>
                            <th class="pb-2">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-900">
                        @foreach($referralDetails as $detail)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ $detail['name'] }}</span>
                                        <span class="text-xs text-gray-500">{{ $detail['email'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="flex flex-col">
                                        <span>{{ $detail['package_name'] }}</span>
                                        <span class="text-xs text-gray-500">${{ number_format($detail['package_deposit'] ?? 0, 2) }}</span>
                                    </div>
                                </td>
                                <td class="py-3">${{ number_format($detail['total_deposited'], 2) }}</td>
                                <td class="py-3">${{ number_format($detail['total_withdrawn'], 2) }}</td>
                                <td class="py-3">${{ number_format($detail['balance'] ?? 0, 2) }}</td>
                                <td class="py-3">{{ number_format($detail['points'] ?? 0) }}</td>
                                <td class="py-3 text-xs text-gray-500">{{ optional($detail['joined_at'])->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="flex justify-between items-center">
        <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Back</a>
    </div>
</div>
@endsection
