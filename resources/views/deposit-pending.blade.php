@extends('layouts.user')

@section('title', 'Deposit Pending - Earn Quest')
@section('page-title', 'Deposit Pending Review')

@php
    use Illuminate\Support\Str;
    $packageConfig = $pendingDeposit->package_code ? ($packages[$pendingDeposit->package_code] ?? null) : null;
    $reference = $pendingDeposit->payment_id;
    if (!$reference && $pendingDeposit->notes) {
        $reference = trim(Str::after($pendingDeposit->notes, 'TX Reference: '));
    }
    $reference = $reference ?: null;
@endphp

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 px-6 py-6 text-white">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/20">
                        <i class="fas fa-hourglass-half"></i>
                    </span>
                    Deposit Under Review
                </h2>
                <p class="text-sm text-blue-100 mt-2">
                    Thanks for submitting your activation payment. Our team is verifying the transaction details. You’ll be notified once the deposit is approved.
                </p>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold">Selected Package</p>
                        <p class="text-lg font-bold text-blue-900 mt-1">{{ $packageConfig['name'] ?? 'Pending Confirmation' }}</p>
                        <p class="text-sm text-blue-700 mt-2">
                            Deposit Amount: <span class="font-semibold">${{ number_format($pendingDeposit->amount, 2) }}</span>
                        </p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Submitted On</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">{{ optional($pendingDeposit->created_at)->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-slate-600 mt-2">Reference: <span class="font-mono">{{ $reference ?? '—' }}</span></p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-wallet text-slate-500"></i>
                        Bound Withdrawal Wallet
                    </h3>
                    <p class="text-sm text-slate-700 font-mono break-all">{{ $user->bep20_address }}</p>
                    <p class="text-xs text-slate-500 mt-1">
                        All payouts will be sent to this address. Contact support if you need to request a change.
                    </p>
                </div>

                @if($pendingDeposit->receipt_path)
                    <div class="border border-blue-100 rounded-xl p-4 bg-blue-50">
                        <h3 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-file-image"></i>
                            Payment Proof
                        </h3>
                        <p class="text-xs text-blue-700 mb-3">
                            We received your upload. You can view the file below while it's under review.
                        </p>
                        @php
                            $receiptUrl = asset('storage/' . ltrim($pendingDeposit->receipt_path, '/'));
                        @endphp
                        <a href="{{ $receiptUrl }}"
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-eye mr-2"></i> View Uploaded Receipt
                        </a>
                    </div>
                @endif

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fas fa-info-circle text-yellow-500 text-lg mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">What’s Next?</p>
                        <ul class="text-sm text-yellow-700 list-disc list-inside mt-2 space-y-1">
                            <li>Verification typically takes up to 24 business hours.</li>
                            <li>Ensure your referral and task requirements are ready once the deposit is approved.</li>
                            <li>If you need to update or cancel the request, contact support with your transaction reference.</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                    <a href="mailto:support@earnquest.live" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                        <i class="fas fa-headset mr-2"></i>Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

