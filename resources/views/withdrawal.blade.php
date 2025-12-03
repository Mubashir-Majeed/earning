@extends('layouts.user')

@section('title', 'Request Withdrawal - Earn Quest')
@section('page-title', 'Request Withdrawal')

@section('quick-videos', $stats['total_videos_watched'])
@section('quick-earnings', '$' . number_format($stats['total_earnings'], 2))

@section('content')
    @php
        $packageCatalog = config('investment.packages');
        $packageConfig = $package ?? ($user->investment_package ? ($packageCatalog[$user->investment_package] ?? null) : null);
        $progressCollection = collect($referralProgress);
        $primaryRule = $progressCollection->firstWhere('is_alternative', false);
        $alternativeRules = $progressCollection->filter(fn ($rule) => $rule['is_alternative']);
        $availableProfit = max(0, $stats['balance'] - ($user->unwithdrawable_balance_min ?? 0));
        $withdrawalCap = $packageConfig['withdrawal_cap'] ?? $availableProfit;
        $maxWithdrawable = min($availableProfit, $withdrawalCap);
        $minWithdrawalValue = $minWithdrawal ?? config('platform.min_withdrawal', 10);
        $requiredReferralValue = $user->requiredReferralValue();
        $currentReferralValue = $user->totalReferralValue();
    @endphp

    <div class="max-w-4xl mx-auto">
        <!-- Requirements Check -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Withdrawal Requirements</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <!-- Withdrawable Profit -->
                <div class="flex items-center p-4 rounded-lg {{ $availableProfit >= $minWithdrawalValue ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $availableProfit >= $minWithdrawalValue ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium {{ $availableProfit >= $minWithdrawalValue ? 'text-green-800' : 'text-red-800' }}">Withdrawable Profit</h4>
                        <p class="text-sm {{ $availableProfit >= $minWithdrawalValue ? 'text-green-700' : 'text-red-700' }}">${{ number_format($availableProfit, 2) }} / ${{ number_format($minWithdrawalValue, 2) }} required</p>
                    </div>
                </div>

                <!-- Referral Requirement -->
                <div class="flex items-center p-4 rounded-lg {{ $user->meetsReferralRequirementForWithdrawal() ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $user->meetsReferralRequirementForWithdrawal() ? 'fa-check-circle text-green-500' : 'fa-users text-yellow-500' }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium {{ $user->meetsReferralRequirementForWithdrawal() ? 'text-green-800' : 'text-yellow-800' }}">Referral Requirement</h4>
                        <p class="text-sm {{ $user->meetsReferralRequirementForWithdrawal() ? 'text-green-700' : 'text-yellow-700' }}">
                            @if($user->investment_package)
                                ${{ number_format($currentReferralValue, 2) }} / ${{ number_format($requiredReferralValue, 2) }} required
                            @else
                                Awaiting package confirmation
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Channel Subscription -->
                <div class="flex items-center p-4 rounded-lg {{ $user->hasSubscribedChannel() ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $user->hasSubscribedChannel() ? 'fa-check-circle text-green-500' : 'fa-play text-yellow-500' }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium {{ $user->hasSubscribedChannel() ? 'text-green-800' : 'text-yellow-800' }}">Channel Subscription</h4>
                        <p class="text-sm {{ $user->hasSubscribedChannel() ? 'text-green-700' : 'text-yellow-700' }}">{{ $user->hasSubscribedChannel() ? 'Confirmed' : 'Confirm via Task Center' }}</p>
                    </div>
                </div>

                <!-- Wallet Binding -->
                <div class="flex items-center p-4 rounded-lg {{ $user->hasBoundWallet() ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $user->hasBoundWallet() ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium {{ $user->hasBoundWallet() ? 'text-green-800' : 'text-red-800' }}">BEP20 Wallet Binding</h4>
                        <p class="text-sm {{ $user->hasBoundWallet() ? 'text-green-700' : 'text-red-700' }}">{{ $user->hasBoundWallet() ? \Illuminate\Support\Str::limit($user->bep20_address, 18, '...') : 'Bind wallet below' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 uppercase tracking-wide mb-1">Package Snapshot</p>
                    <p class="text-sm text-blue-700">
                        @if($packageConfig)
                            {{ $packageConfig['name'] }} package • Deposit ${{ number_format($packageConfig['deposit_amount'], 2) }} • Withdrawal cap ${{ number_format($packageConfig['withdrawal_cap'], 2) }}
                        @else
                            Package will be assigned once your deposit is approved.
                        @endif
                    </p>
                </div>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Monthly Withdrawal Quota</p>
                    <p class="text-sm text-gray-600">{{ $user->monthly_withdrawals_count }} of {{ $user->withdrawalMonthlyLimit() }} withdrawals used this month.</p>
                </div>
            </div>

            @if($alternativeRules->isNotEmpty())
                <div class="mt-3 bg-blue-50 border border-blue-100 rounded-lg p-3 text-xs text-blue-700">
                    <p class="font-semibold mb-1">Alternative referral paths available:</p>
                    <ul class="space-y-1">
                        @foreach($alternativeRules as $rule)
                            <li>• {{ $rule['current'] }} / {{ $rule['required'] }} {{ data_get($packageCatalog, $rule['package'].'.name', strtoupper($rule['package'])) }} referrals — {{ $rule['description'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$user->canWithdraw())
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800">Requirements Not Met</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                You must meet all requirements above before you can make a withdrawal request.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Wallet Binding Section - Show if wallet not bound -->
        @if(!$user->hasBoundWallet())
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Bind Your BEP20 Wallet</h3>
                <p class="text-gray-600">You must bind your BEP20 wallet address before requesting a withdrawal. This address will be locked after binding.</p>
            </div>

            <form method="POST" action="{{ route('withdrawal.bind-wallet') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="bep20_address" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-wallet mr-2 text-blue-600"></i>BEP20 Wallet Address
                    </label>
                    <input 
                        type="text" 
                        id="bep20_address" 
                        name="bep20_address" 
                        value="{{ old('bep20_address') }}"
                        placeholder="0x..." 
                        required
                        pattern="^0x[a-fA-F0-9]{40}$"
                        class="w-full px-4 py-3 border-2 {{ $errors->has('bep20_address') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono text-sm bg-white"
                    />
                    @error('bep20_address')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-600 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>Enter a valid BEP20 wallet address (0x followed by 40 hexadecimal characters). This address will be locked after binding.
                    </p>
                </div>
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-link mr-2"></i>Bind Wallet Address
                </button>
            </form>
        </div>
        @endif

        @if($user->canWithdraw() && $user->hasBoundWallet())
        <!-- Withdrawal Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Withdrawal Request</h3>
                <p class="text-gray-600">Request a withdrawal of your earnings. Processing takes 48 hours.</p>
            </div>

            <!-- Balance Info -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-wallet text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">Account Balance</h4>
                            <p class="text-3xl font-bold text-blue-900">${{ number_format($stats['balance'], 2) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Max withdraw this request</p>
                        <p class="text-lg font-semibold text-green-600">${{ number_format($maxWithdrawable, 2) }}</p>
                        <p class="text-xs text-gray-500">Cap enforced: ${{ number_format($withdrawalCap, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Form -->
            <form action="{{ route('withdrawal.request') }}" method="POST" id="withdrawal-form">
                @csrf
                <input type="hidden" name="otp_verified" id="otp_verified" value="0">
                <div class="space-y-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-blue-500"></i>Withdrawal Amount
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="{{ $maxWithdrawable >= $minWithdrawalValue ? $minWithdrawalValue : max(0, $maxWithdrawable) }}" 
                                   max="{{ $maxWithdrawable }}"
                                   value="{{ $maxWithdrawable >= $minWithdrawalValue ? $minWithdrawalValue : max(0, $maxWithdrawable) }}"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Minimum: ${{ number_format($minWithdrawalValue, 2) }} | Maximum: ${{ number_format($maxWithdrawable, 2) }} (package cap & retained deposit apply)
                        </p>
                    </div>

                    <!-- Withdrawal Wallet Details (Read-only if bound) -->
                    <div class="border-2 border-green-200 rounded-xl p-6 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 text-xl mr-2"></i>
                                <h4 class="text-lg font-semibold text-green-900">Wallet Bound Successfully</h4>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-300">
                                <i class="fas fa-lock mr-1"></i>Locked
                            </span>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-green-200">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">BEP20 Wallet Address</p>
                            <p class="text-sm text-gray-900 font-mono break-all">{{ $user->bep20_address }}</p>
                        </div>
                        <p class="mt-3 text-xs text-gray-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>Withdrawals will be processed to this wallet address. Contact support to update it.
                        </p>
                    </div>

                    <!-- Fee Calculation -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-calculator text-blue-500 mr-2"></i>
                            <h4 class="text-lg font-semibold text-gray-900">Withdrawal Summary</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 font-medium">Withdrawal Amount:</span>
                                <span id="withdrawal-amount" class="font-bold text-lg">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-t border-gray-200">
                                <span class="text-gray-600 font-medium">Processing Fee ({{ $user->withdrawalFeePercent() * 100 }}%):</span>
                                <span id="processing-fee" class="font-bold text-red-600">-$0.00</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 bg-white rounded-lg px-4">
                                <span class="text-lg font-semibold text-gray-900">You will receive:</span>
                                <span id="net-amount" class="text-2xl font-bold text-green-600">$0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">Terms and Conditions</a> and understand that withdrawals take 48 hours to process with a {{ $user->withdrawalFeePercent() * 100 }}% fee.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors border border-gray-300">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-2"></i>Request Withdrawal
                        </button>
                    </div>
                </div>
            </form>

            <!-- Important Notice -->
            <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-yellow-800 mb-3">Important Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-yellow-700">
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Withdrawals processed within 48 hours</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-percentage mr-2"></i>
                                    <span>{{ $user->withdrawalFeePercent() * 100 }}% processing fee applies</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-dollar-sign mr-2"></i>
                                    <span>Minimum withdrawal amount is ${{ number_format($minWithdrawalValue, 2) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-lock mr-2"></i>
                                    <span>Initial deposit of ${{ number_format($user->unwithdrawable_balance_min ?? 0, 2) }} remains locked.</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    <span>Per-request cap: ${{ number_format($withdrawalCap, 2) }}</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>Ensure your bound BEP20 wallet is correct</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-headset mr-2"></i>
                                    <span>Contact support for any questions</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    <span>Secure BEP20 transactions only</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Requirements Not Met -->
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Withdrawal Not Available</h3>
            <p class="text-gray-600 mb-6">You need to meet all requirements before you can make a withdrawal request.</p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    const amountInput = document.getElementById('amount');
    const withdrawalAmount = document.getElementById('withdrawal-amount');
    const processingFee = document.getElementById('processing-fee');
    const netAmount = document.getElementById('net-amount');
    const feePercentage = {{ $user->withdrawalFeePercent() }};
    
    function updateWithdrawalSummary() {
        const amount = parseFloat(amountInput.value) || 0;
        const fee = amount * feePercentage;
        const net = amount - fee;
        
        withdrawalAmount.textContent = '$' + amount.toFixed(2);
        processingFee.textContent = '-$' + fee.toFixed(2);
        netAmount.textContent = '$' + net.toFixed(2);
    }
    
    if (amountInput) {
        amountInput.addEventListener('input', updateWithdrawalSummary);
        
        // Initial calculation
        updateWithdrawalSummary();
        
        // Form validation is now handled in the OTP modal section below
    }

    // OTP Verification Modal
    let withdrawalOtpTimer = null;
    let withdrawalTimeLeft = 180;
    let isWithdrawalOtpVerified = false;

    // Send OTP for withdrawal
    async function sendWithdrawalOtp(isResend = false) {
        const sendBtn = document.getElementById('modal-send-otp-btn');
        const sendText = document.getElementById('modal-send-otp-text');
        const sendSpinner = document.getElementById('modal-send-otp-spinner');
        const resendBtn = document.getElementById('modal-resend-otp-btn');

        if (sendBtn) {
            sendBtn.disabled = true;
            sendText.classList.add('hidden');
            sendSpinner.classList.remove('hidden');
        }

        try {
            const response = await fetch('{{ route("withdrawal.send-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                showWithdrawalMessage('modal-otp-message', data.message, 'success');
                document.getElementById('modal-otp-input').focus();
                
                // Start timer
                withdrawalTimeLeft = data.expires_in || 180;
                startWithdrawalTimer();
                
                // Hide resend button initially
                if (resendBtn) {
                    resendBtn.classList.add('hidden');
                }
                
                // Re-enable send button
                if (sendBtn && isResend) {
                    setTimeout(() => {
                        sendBtn.disabled = false;
                        sendText.classList.remove('hidden');
                        sendSpinner.classList.add('hidden');
                    }, 1000);
                } else if (sendBtn) {
                    sendBtn.disabled = false;
                    sendText.classList.remove('hidden');
                    sendSpinner.classList.add('hidden');
                }
            } else {
                showWithdrawalMessage('modal-otp-message', data.message, 'error');
                if (sendBtn) {
                    if (data.retry_after) {
                        setTimeout(() => {
                            sendBtn.disabled = false;
                            sendText.classList.remove('hidden');
                            sendSpinner.classList.add('hidden');
                        }, data.retry_after * 1000);
                    } else {
                        sendBtn.disabled = false;
                        sendText.classList.remove('hidden');
                        sendSpinner.classList.add('hidden');
                    }
                }
            }
        } catch (error) {
            showWithdrawalMessage('modal-otp-message', 'An error occurred. Please try again.', 'error');
            if (sendBtn) {
                sendBtn.disabled = false;
                sendText.classList.remove('hidden');
                sendSpinner.classList.add('hidden');
            }
        }
    }

    // Verify OTP for withdrawal
    async function verifyWithdrawalOtp() {
        const otpInput = document.getElementById('modal-otp-input');
        const otp = otpInput.value.trim();

        if (otp.length !== 6) {
            showWithdrawalMessage('modal-otp-verify-message', 'Please enter a 6-digit OTP code.', 'error');
            return;
        }

        try {
            const response = await fetch('{{ route("withdrawal.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ otp })
            });

            const data = await response.json();

            if (data.success) {
                isWithdrawalOtpVerified = true;
                document.getElementById('otp_verified').value = '1';
                showWithdrawalMessage('modal-otp-verify-message', data.message, 'success');
                otpInput.classList.add('border-green-500');
                otpInput.disabled = true;
                clearWithdrawalTimer();
                
                // Close modal and submit form
                setTimeout(() => {
                    closeOtpModal();
                    document.getElementById('withdrawal-form').submit();
                }, 1000);
            } else {
                showWithdrawalMessage('modal-otp-verify-message', data.message, 'error');
                otpInput.classList.add('border-red-500');
                setTimeout(() => {
                    otpInput.classList.remove('border-red-500');
                }, 2000);
            }
        } catch (error) {
            showWithdrawalMessage('modal-otp-verify-message', 'An error occurred. Please try again.', 'error');
        }
    }

    // Timer functions
    function startWithdrawalTimer() {
        clearWithdrawalTimer();
        updateWithdrawalTimerDisplay();
        withdrawalOtpTimer = setInterval(() => {
            withdrawalTimeLeft--;
            updateWithdrawalTimerDisplay();
            if (withdrawalTimeLeft <= 0) {
                clearWithdrawalTimer();
                const resendBtn = document.getElementById('modal-resend-otp-btn');
                if (resendBtn) {
                    resendBtn.classList.remove('hidden');
                }
            }
        }, 1000);
    }

    function clearWithdrawalTimer() {
        if (withdrawalOtpTimer) {
            clearInterval(withdrawalOtpTimer);
            withdrawalOtpTimer = null;
        }
    }

    function updateWithdrawalTimerDisplay() {
        const minutes = Math.floor(withdrawalTimeLeft / 60);
        const seconds = withdrawalTimeLeft % 60;
        const timerText = document.getElementById('modal-timer-text');
        if (timerText) {
            timerText.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (withdrawalTimeLeft <= 30) {
                timerText.classList.add('text-red-600');
            } else {
                timerText.classList.remove('text-red-600');
            }
        }
    }

    // Modal functions
    function openOtpModal() {
        const modal = document.getElementById('otp-verification-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Auto-send OTP when modal opens
            sendWithdrawalOtp();
        }
    }

    function closeOtpModal() {
        const modal = document.getElementById('otp-verification-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            clearWithdrawalTimer();
        }
    }

    // Helper functions
    function showWithdrawalMessage(elementId, message, type) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        element.classList.remove('hidden');
        element.className = element.className.replace(/text-(red|green|blue)-600/g, '');
        
        if (type === 'success') {
            element.className += ' text-green-600';
            element.innerHTML = `<i class="fas fa-check-circle mr-1"></i>${message}`;
        } else if (type === 'error') {
            element.className += ' text-red-600';
            element.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
        } else {
            element.className += ' text-blue-600';
            element.innerHTML = `<i class="fas fa-info-circle mr-1"></i>${message}`;
        }
    }

    // Update form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('withdrawal-form');
        const otpInput = document.getElementById('modal-otp-input');
        const amountInput = document.getElementById('amount');

        if (form) {
            form.addEventListener('submit', function(e) {
                // Check if OTP is verified
                if (!isWithdrawalOtpVerified) {
                    e.preventDefault();
                    
                    // Validate form first
                    const amount = parseFloat(amountInput.value);
                    const maxAmount = {{ max(0, $maxWithdrawable) }};
                    const minAmount = {{ $minWithdrawalValue }};
                    
                    if (!amount || isNaN(amount)) {
                        alert('Please enter a valid withdrawal amount.');
                        amountInput.focus();
                        return false;
                    }
                    
                    if (amount < minAmount) {
                        alert('Minimum withdrawal amount is $' + minAmount.toFixed(2));
                        amountInput.focus();
                        return false;
                    }
                    
                    if (amount > maxAmount) {
                        alert('Withdrawal amount cannot exceed your withdrawable balance of $' + maxAmount.toFixed(2));
                        amountInput.focus();
                        return false;
                    }
                    
                    // Check terms checkbox
                    const termsCheckbox = document.getElementById('terms');
                    if (!termsCheckbox.checked) {
                        alert('Please agree to the Terms and Conditions.');
                        termsCheckbox.focus();
                        return false;
                    }
                    
                    // Open OTP modal
                    openOtpModal();
                    return false;
                }
            });
        }

        // OTP input - auto verify on 6 digits
        if (otpInput) {
            otpInput.addEventListener('input', function() {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value.length === 6 && !isWithdrawalOtpVerified) {
                    verifyWithdrawalOtp();
                }
            });

            // Allow Enter key to verify
            otpInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.length === 6) {
                    e.preventDefault();
                    verifyWithdrawalOtp();
                }
            });
        }

        // Close modal on backdrop click
        const modal = document.getElementById('otp-verification-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeOtpModal();
                }
            });
        }
    });
</script>

<!-- OTP Verification Modal -->
<div id="otp-verification-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-6 animate-fade-in">
        <!-- Modal Header -->
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Email Verification</h3>
                <p class="text-sm text-gray-600 mt-1">Verify your email to complete withdrawal request</p>
            </div>
            <button type="button" onclick="closeOtpModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Email Display -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-xs font-semibold text-blue-800 uppercase tracking-wide mb-1">Verification Email</p>
            <p class="text-sm text-gray-900 font-mono">{{ auth()->user()->email }}</p>
        </div>

        <!-- OTP Message -->
        <div id="modal-otp-message" class="hidden"></div>

        <!-- OTP Input Section -->
        <div>
            <label for="modal-otp-input" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-key mr-2 text-blue-600"></i>Enter OTP Code
            </label>
            <div class="relative">
                <input id="modal-otp-input" 
                       type="text" 
                       maxlength="6"
                       autocomplete="off"
                       class="w-full px-4 py-4 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-center text-3xl font-mono tracking-widest"
                       placeholder="000000">
            </div>
            <div class="flex items-center justify-between mt-3">
                <div id="modal-otp-timer" class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="modal-timer-text">03:00</span>
                </div>
                <button type="button" 
                        id="modal-resend-otp-btn"
                        onclick="sendWithdrawalOtp(true)"
                        class="hidden text-sm text-blue-600 hover:text-blue-700 transition-colors font-medium">
                    <i class="fas fa-redo mr-1"></i>Resend OTP
                </button>
            </div>
            <div id="modal-otp-verify-message" class="hidden mt-3"></div>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3 pt-4 border-t border-gray-200">
            <button type="button" 
                    id="modal-send-otp-btn"
                    onclick="sendWithdrawalOtp()"
                    class="hidden flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="modal-send-otp-text">Send OTP</span>
                <span id="modal-send-otp-spinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button type="button" 
                    onclick="closeOtpModal()"
                    class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                Cancel
            </button>
        </div>

        <!-- Info -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
            <p class="text-xs text-yellow-800">
                <i class="fas fa-info-circle mr-1"></i>
                OTP code has been sent to your email. It will expire in 3 minutes.
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endsection
