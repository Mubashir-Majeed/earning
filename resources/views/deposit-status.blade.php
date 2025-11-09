@extends('layouts.user')

@section('title', 'Deposit Status - Earn Quest')
@section('page-title', 'Deposit Status')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $packageConfig = $depositRecord->package_code ? ($packages[$depositRecord->package_code] ?? null) : null;
    $status = $depositRecord->status;
    $reference = $depositRecord->payment_id;
    if (!$reference && $depositRecord->notes) {
        $reference = trim(Str::after($depositRecord->notes, 'TX Reference: '));
    }
    $reference = $reference ?: null;
@endphp

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="{{ $status === 'completed' ? 'bg-gradient-to-r from-green-500 via-emerald-500 to-green-600' : ($status === 'failed' ? 'bg-gradient-to-r from-red-500 via-rose-500 to-red-600' : 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500') }} px-6 py-6 text-white">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/20">
                        @if($status === 'completed')
                            <i class="fas fa-check-circle"></i>
                        @elseif($status === 'failed')
                            <i class="fas fa-times-circle"></i>
                        @else
                            <i class="fas fa-hourglass-half"></i>
                        @endif
                    </span>
                    @if($status === 'completed')
                        Deposit Approved
                    @elseif($status === 'failed')
                        Deposit Rejected
                    @else
                        Deposit In Review
                    @endif
                </h2>
                <p class="text-sm text-white/80 mt-2">
                    @if($status === 'completed')
                        Your package is active. You can continue earning through tasks and referrals.
                    @elseif($status === 'failed')
                        The deposit was not approved. Review the reason below and try again if necessary.
                    @else
                        Our team is still reviewing your deposit details. You will be notified soon.
                    @endif
                </p>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Package</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">{{ $packageConfig['name'] ?? '—' }}</p>
                        <p class="text-sm text-slate-600 mt-2">Deposit Amount: <span class="font-semibold">${{ number_format($depositRecord->amount, 2) }}</span></p>
                        @if($packageConfig)
                            <p class="text-xs text-slate-500 mt-1">Withdrawal Cap: ${{ number_format($packageConfig['withdrawal_cap'], 2) }}</p>
                        @endif
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Submitted</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">{{ optional($depositRecord->created_at)->format('M d, Y h:i A') }}</p>
                        <p class="text-xs text-slate-500 mt-1">Transaction ID:</p>
                        <p class="text-sm font-mono text-slate-700 break-all">{{ $reference ?? '—' }}</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-wallet text-slate-500"></i>
                        Bound Withdrawal Wallet
                    </h3>
                    <p class="text-sm text-slate-700 font-mono break-all">{{ $user->bep20_address }}</p>
                    <p class="text-xs text-slate-500 mt-1">
                        Withdrawals will be sent to this address once you meet all requirements.
                    </p>
                </div>

                @if($depositRecord->receipt_path)
                    @php
                        $receiptUrl = asset('storage/' . ltrim($depositRecord->receipt_path, '/'));
                    @endphp
                    <div class="border border-blue-100 rounded-xl p-4 bg-blue-50">
                        <h3 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-file-image"></i>
                            Payment Proof
                        </h3>
                        <a href="{{ $receiptUrl }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-eye mr-2"></i>View Uploaded Receipt
                        </a>
                    </div>
                @endif

                @if($status === 'failed' && $depositRecord->notes)
                    <div class="border border-red-200 rounded-xl p-4 bg-red-50 flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg mt-1"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Why was it rejected?</p>
                            <p class="text-sm text-red-700 mt-1">{{ $depositRecord->notes }}</p>
                        </div>
                    </div>
                @elseif($status === 'completed')
                    <div class="border border-green-200 rounded-xl p-4 bg-green-50 flex items-start gap-3">
                        <i class="fas fa-check text-green-500 text-lg mt-1"></i>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Next Steps</p>
                            <ul class="text-sm text-green-700 list-disc list-inside mt-1 space-y-1">
                                <li>Complete your daily video tasks to earn rewards.</li>
                                <li>Invite referrals to meet withdrawal requirements.</li>
                                <li>Monitor your progress from this dashboard anytime.</li>
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                    <div class="flex items-center gap-2">
                        @if($status === 'failed')
                            <a href="{{ route('deposit', ['retry' => 1]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-redo mr-2"></i>Submit New Deposit
                            </a>
                        @elseif($status === 'completed')
                            <a href="{{ route('deposit', ['new' => 1]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus-circle mr-2"></i>Make Another Deposit
                            </a>
                        @endif
                        <a href="mailto:support@earnquest.live" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                            <i class="fas fa-headset mr-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

