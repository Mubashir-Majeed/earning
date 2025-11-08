@extends('layouts.user')

@section('title', 'Request Withdrawal - Earn Quest')
@section('page-title', 'Request Withdrawal')

@section('quick-videos', $stats['total_videos_watched'])
@section('quick-earnings', '$' . number_format($stats['total_earnings'], 2))
@section('quick-points', number_format($stats['total_points']))

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
        $minWithdrawal = 10;
    @endphp

    <div class="max-w-4xl mx-auto">
        <!-- Requirements Check -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Withdrawal Requirements</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <!-- Withdrawable Profit -->
                <div class="flex items-center p-4 rounded-lg {{ $availableProfit >= $minWithdrawal ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $availableProfit >= $minWithdrawal ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium {{ $availableProfit >= $minWithdrawal ? 'text-green-800' : 'text-red-800' }}">Withdrawable Profit</h4>
                        <p class="text-sm {{ $availableProfit >= $minWithdrawal ? 'text-green-700' : 'text-red-700' }}">${{ number_format($availableProfit, 2) }} / ${{ number_format($minWithdrawal, 2) }} required</p>
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
                            @if($primaryRule)
                                {{ $primaryRule['current'] }} / {{ $primaryRule['required'] }} {{ data_get($packageCatalog, $primaryRule['package'].'.name', strtoupper($primaryRule['package'])) }} referrals
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
                        <p class="text-sm {{ $user->hasBoundWallet() ? 'text-green-700' : 'text-red-700' }}">{{ $user->hasBoundWallet() ? \Illuminate\Support\Str::limit($user->bep20_address, 18, '...') : 'Bind wallet in profile' }}</p>
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

        @if($user->canWithdraw())
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
                                   min="{{ $maxWithdrawable >= $minWithdrawal ? $minWithdrawal : max(0, $maxWithdrawable) }}" 
                                   max="{{ max(0, $maxWithdrawable) }}"
                                   value="{{ $maxWithdrawable >= $minWithdrawal ? $minWithdrawal : max(0, $maxWithdrawable) }}"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Minimum: ${{ number_format($minWithdrawal, 2) }} | Maximum: ${{ number_format($maxWithdrawable, 2) }} (package cap & retained deposit apply)
                        </p>
                    </div>

                    <!-- Withdrawal Wallet Details -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-link text-blue-500 mr-2"></i>
                            <h4 class="text-sm font-semibold text-gray-900">Withdrawal Wallet (BEP20)</h4>
                        </div>
                        <p class="text-sm text-gray-700 font-mono break-all">{{ $user->bep20_address }}</p>
                        <p class="mt-2 text-xs text-gray-500">
                            Withdrawals are only processed to this bound BEP20 wallet. Update it from your profile before submitting a request.
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
                                    <span>Minimum withdrawal amount is ${{ number_format($minWithdrawal, 2) }}</span>
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
        
        // Form validation
        document.getElementById('withdrawal-form').addEventListener('submit', function(e) {
            const amount = parseFloat(amountInput.value);
            const maxAmount = {{ max(0, $maxWithdrawable) }};
            
            if (amount < {{ $minWithdrawal }}) {
                e.preventDefault();
                alert('Minimum withdrawal amount is ${{ number_format($minWithdrawal, 2) }}');
                return;
            }
            
            if (amount > maxAmount) {
                e.preventDefault();
                alert('Withdrawal amount cannot exceed your withdrawable balance of $' + maxAmount.toFixed(2));
                return;
            }
            
        });
    }
</script>
@endsection
