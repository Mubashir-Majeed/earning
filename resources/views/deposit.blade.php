@extends('layouts.user')

@section('title', 'Activate Package - Earn Quest')
@section('page-title', 'Make Deposit')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if($isRedeposit)
                            Upgrade Your Package
                        @else
                            Choose Your Earn Quest Package
                        @endif
                    </h3>
                    <p class="text-gray-600">
                        @if($isRedeposit)
                            Upgrade to a higher package by adding the difference amount. Your deposit stays as locked capital while you earn on top.
                        @else
                            Select a package to unlock video tasks, referral rewards, and withdrawals. Your deposit stays as locked capital while you earn on top.
                        @endif
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 border border-red-200 bg-red-50 text-red-700 rounded-lg p-4">
                        <p class="text-sm font-semibold mb-2">Please address the following:</p>
                        <ul class="text-sm list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    // For redeposit, select first available upgrade package
                    // For new deposit, use default or pending
                    if ($isRedeposit && count($packages) > 0) {
                        $firstUpgradeCode = array_key_first($packages);
                        $selectedPackage = old('package_code', $user->pending_package_code ?? $firstUpgradeCode);
                    } else {
                        $selectedPackage = old('package_code', $user->pending_package_code ?? $user->investment_package ?? 'starter_35');
                    }
                @endphp

                <form action="{{ route('payment.deposit') }}" method="POST" id="payment-form" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-8">
                        <!-- Package Selection -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">
                                @if($isRedeposit)
                                    Available Upgrades
                                @else
                                    Packages
                                @endif
                            </h4>
                            @if(count($packages) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-{{ count($packages) > 2 ? '3' : count($packages) }} gap-4">
                                    @foreach($packages as $code => $package)
                                        @php $isActive = $selectedPackage === $code; @endphp
                                        <label data-package-card class="package-card relative block border rounded-xl p-4 cursor-pointer transition {{ $isActive ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200 hover:border-blue-300' }}">
                                            <input type="radio" name="package_code" value="{{ $code }}" class="sr-only" {{ $isActive ? 'checked' : '' }}>
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $package['name'] }} Package</p>
                                                    @if($isRedeposit && isset($package['amount_to_add']))
                                                        <p class="text-2xl font-bold text-blue-600 mt-1">+${{ number_format($package['amount_to_add'], 2) }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">Add ${{ number_format($package['amount_to_add'], 2) }} to upgrade</p>
                                                    @else
                                                        <p class="text-2xl font-bold text-blue-600 mt-1">${{ number_format($package['deposit_amount'], 2) }}</p>
                                                    @endif
                                                </div>
                                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                                    <i class="fas fa-gem"></i>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-4 leading-snug">{{ $package['description'] }}</p>
                                            @if($isRedeposit && isset($package['amount_to_add']))
                                                <div class="mt-3 pt-3 border-t border-gray-200">
                                                    <p class="text-xs text-gray-600">
                                                        <span class="font-medium">Current:</span> ${{ number_format($package['current_package_amount'], 2) }} → 
                                                        <span class="font-medium">New:</span> ${{ number_format($package['deposit_amount'], 2) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="border border-gray-200 rounded-xl p-6 text-center bg-gray-50">
                                    <i class="fas fa-info-circle text-gray-400 text-3xl mb-3"></i>
                                    <p class="text-gray-600 font-medium">
                                        @if($isRedeposit)
                                            No upgrade packages available. You already have the highest package.
                                        @else
                                            No packages available at the moment. Please contact support.
                                        @endif
                                    </p>
                                </div>
                            @endif
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
                                <p class="text-xs text-blue-700 mb-2">
                                    @if($isRedeposit)
                                        Send the upgrade amount (difference) to this BEP20 address. It is configured by Earn Quest and cannot be edited by members.
                                    @else
                                        Send your package amount to this BEP20 address. It is configured by Earn Quest and cannot be edited by members.
                                    @endif
                                </p>
                                @if($isRedeposit && count($packages) > 0)
                                    <div class="mb-2 p-2 bg-blue-100 rounded-lg">
                                        <p class="text-xs font-semibold text-blue-900">
                                            Amount to Send: <span class="text-base font-bold" data-amount-display>$0.00</span>
                                        </p>
                                        <p class="text-xs text-blue-700 mt-1">Select a package above to see the exact amount</p>
                                    </div>
                                @endif
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
                        <div class="flex items-start gap-3 sm:gap-2">
                            <div class="flex items-center pt-0.5 sm:pt-0 flex-shrink-0">
                                <input id="terms" name="terms" type="checkbox" required class="focus:ring-blue-500 h-5 w-5 sm:h-4 sm:w-4 text-blue-600 border-gray-300 rounded cursor-pointer flex-shrink-0">
                            </div>
                            <div class="flex-1 min-w-0">
                                <label for="terms" class="text-sm sm:text-sm text-gray-700 cursor-pointer leading-relaxed block">
                                    I confirm that this deposit is final, my initial capital remains locked, and withdrawals require referral completion per package rules.
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300">
                                Cancel
                            </a>
                            @if(count($packages) > 0)
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    @if($isRedeposit)
                                        Submit Upgrade Request
                                    @else
                                        Submit Deposit Request
                                    @endif
                                </button>
                            @else
                                <button type="button" disabled class="bg-gray-400 text-white px-6 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                                    No Packages Available
                                </button>
                            @endif
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
            
            // Update amount display for redeposit
            @if($isRedeposit && count($packages) > 0)
            const amountDisplay = document.querySelector('[data-amount-display]');
            if (amountDisplay) {
                const checkedInput = document.querySelector('input[name="package_code"]:checked');
                if (checkedInput) {
                    const card = checkedInput.closest('[data-package-card]');
                    const amountText = card.querySelector('.text-2xl').textContent;
                    // Extract amount from text like "+$15.00" or "$50.00"
                    const amountMatch = amountText.match(/[\d.]+/);
                    if (amountMatch) {
                        const amount = parseFloat(amountMatch[0]);
                        amountDisplay.textContent = '$' + amount.toFixed(2);
                    }
                } else {
                    amountDisplay.textContent = '$0.00';
                }
            }
            @endif
        }

        inputs.forEach(input => {
            input.addEventListener('change', refreshCards);
        });

        // Initial refresh to set default selected package amount
        refreshCards();
        
        // Also update on page load if a package is already selected
        @if($isRedeposit && count($packages) > 0)
        setTimeout(() => {
            refreshCards();
        }, 100);
        @endif

        const copyBtn = document.querySelector('[data-wallet-copy]');
        const walletInput = document.querySelector('[data-wallet-value]');
        if (copyBtn && walletInput) {
            const updateButtonState = (text, resetAfter = true) => {
                copyBtn.textContent = text;
                if (resetAfter) {
                    setTimeout(() => copyBtn.textContent = 'Copy', 1500);
                }
            };

            const fallbackCopy = (value) => {
                const temp = document.createElement('textarea');
                temp.value = value;
                temp.setAttribute('readonly', '');
                temp.style.position = 'absolute';
                temp.style.left = '-9999px';
                document.body.appendChild(temp);
                temp.select();
                temp.setSelectionRange(0, temp.value.length);
                const success = document.execCommand('copy');
                document.body.removeChild(temp);
                return success;
            };

            copyBtn.addEventListener('click', async () => {
                const value = walletInput.value || '';
                let copied = false;

                if (navigator.clipboard?.writeText) {
                    try {
                        await navigator.clipboard.writeText(value);
                        copied = true;
                    } catch {
                        copied = fallbackCopy(value);
                    }
                } else {
                    copied = fallbackCopy(value);
                }

                if (copied) {
                    updateButtonState('Copied!');
                } else {
                    updateButtonState('Copy Failed', false);
                }
            });
        }
    })();
</script>
@endsection
