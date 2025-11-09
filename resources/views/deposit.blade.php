@extends('layouts.user')

@section('title', 'Activate Package - Earn Quest')
@section('page-title', 'Make Deposit')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Choose Your Earn Quest Package</h3>
                    <p class="text-gray-600">Select a package to unlock video tasks, referral rewards, and withdrawals. Your deposit stays as locked capital while you earn on top.</p>
                </div>

                @php
                    $selectedPackage = old('package_code', $user->pending_package_code ?? $user->investment_package ?? 'starter_35');
                @endphp

                <form action="{{ route('payment.deposit') }}" method="POST" id="payment-form" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-8">
                        <!-- Package Selection -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Packages</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($packages as $code => $package)
                                    @php $isActive = $selectedPackage === $code; @endphp
                                    <label data-package-card class="package-card relative block border rounded-xl p-4 cursor-pointer transition {{ $isActive ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200 hover:border-blue-300' }}">
                                        <input type="radio" name="package_code" value="{{ $code }}" class="sr-only" {{ $isActive ? 'checked' : '' }}>
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $package['name'] }} Package</p>
                                                <p class="text-2xl font-bold text-blue-600 mt-1">${{ number_format($package['deposit_amount'], 2) }}</p>
                                            </div>
                                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                                <i class="fas fa-gem"></i>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-4 leading-snug">{{ $package['description'] }}</p>
                                    </label>
                                @endforeach
                            </div>
                            @error('package_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Platform Deposit Wallet -->
                        @if(!empty($platformWallet))
                            <div class="border border-blue-100 rounded-xl p-4 bg-blue-50/60">
                                <div class="flex items-center justify-between mb-2 gap-3">
                                    <h4 class="text-sm font-semibold text-blue-800 flex items-center gap-2">
                                        <i class="fas fa-wallet"></i>
                                        Platform Deposit Wallet
                                    </h4>
                                    <button type="button" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition" data-wallet-copy>
                                        Copy
                                    </button>
                                </div>
                                <p class="text-xs text-blue-700 mb-2">Send your package amount to this BEP20 address. It is configured by Earn Quest and cannot be edited by members.</p>
                                <input type="text" value="{{ $platformWallet }}" readonly class="w-full px-4 py-3 border border-blue-200 rounded-lg bg-white font-mono text-sm" data-wallet-value>
                            </div>
                        @else
                            <div class="border border-yellow-200 rounded-xl p-4 bg-yellow-50">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Wallet Pending
                                </h4>
                                <p class="text-xs text-yellow-700">The platform deposit wallet has not been configured yet. Please contact support before sending funds.</p>
                            </div>
                        @endif

                        <!-- Primary Withdrawal Wallet -->
                        @if($user->hasBoundWallet())
                            <input type="hidden" name="wallet_address" value="{{ $user->bep20_address }}">
                            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <i class="fas fa-lock"></i>
                                    Bound Withdrawal Wallet
                                </h4>
                                <p class="text-sm text-gray-700 font-mono break-all">{{ $user->bep20_address }}</p>
                                <p class="text-xs text-gray-500 mt-1">Wallet binding is permanent. Contact support if it needs to be updated.</p>
                            </div>
                        @else
                            <div class="border border-yellow-200 rounded-xl p-4 bg-yellow-50">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Wallet Binding Required
                                </h4>
                                <p class="text-xs text-yellow-700">Please add your BEP20 withdrawal wallet in <a href="{{ route('profile.edit') }}" class="underline font-semibold">profile settings</a> before submitting a deposit request.</p>
                            </div>
                        @endif

                        <!-- Transaction Reference -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Reference (required)</label>
                            <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="Enter TX hash or payment note">
                            @error('transaction_reference')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Receipt Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Payment Proof</label>
                            <input type="file" name="transaction_receipt" accept="image/*" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                            <p class="text-xs text-gray-500 mt-1">Attach a screenshot or photo of your transfer. Supported formats: JPG, PNG (max 5 MB).</p>
                            @error('transaction_receipt')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-700">
                                    I confirm that this deposit is final, my initial capital remains locked, and withdrawals require referral completion per package rules.
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-60 disabled:cursor-not-allowed" {{ $user->hasBoundWallet() ? '' : 'disabled' }}>
                                Submit Deposit Request
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Security Notice -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-800">Manual Verification</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Deposits are reviewed by the Earn Quest team. Once verified, your package activates and wallet binding is locked for future withdrawals.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    (function () {
        const cards = document.querySelectorAll('[data-package-card]');
        const inputs = document.querySelectorAll('input[name="package_code"]');

        function refreshCards() {
            cards.forEach(card => {
                const input = card.querySelector('input[name="package_code"]');
                const isChecked = input.checked;
                card.classList.toggle('border-blue-500', isChecked);
                card.classList.toggle('ring-2', isChecked);
                card.classList.toggle('ring-blue-200', isChecked);
                card.classList.toggle('border-gray-200', !isChecked);
                card.classList.toggle('hover:border-blue-300', !isChecked);
            });
        }

        inputs.forEach(input => {
            input.addEventListener('change', refreshCards);
        });

        refreshCards();

        const copyBtn = document.querySelector('[data-wallet-copy]');
        const walletInput = document.querySelector('[data-wallet-value]');
        if (copyBtn && walletInput) {
            copyBtn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(walletInput.value);
                    copyBtn.textContent = 'Copied!';
                    setTimeout(() => copyBtn.textContent = 'Copy', 1200);
                } catch (e) {
                    alert('Unable to copy. Please copy the address manually.');
                }
            });
        }
    })();
</script>
@endsection
