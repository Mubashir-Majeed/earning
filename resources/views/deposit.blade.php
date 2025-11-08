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

                <form action="{{ route('payment.deposit') }}" method="POST" id="payment-form">
                    @csrf
                    <div class="space-y-8">
                        <!-- Package Selection -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Packages</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($packages as $code => $package)
                                    <label class="relative block border rounded-xl p-4 cursor-pointer transition {{ $selectedPackage === $code ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200 hover:border-blue-300' }}">
                                        <input type="radio" name="package_code" value="{{ $code }}" class="sr-only" {{ $selectedPackage === $code ? 'checked' : '' }}>
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $package['name'] }} Package</p>
                                                <p class="text-2xl font-bold text-blue-600 mt-1">${{ number_format($package['deposit_amount'], 2) }}</p>
                                                <p class="text-xs text-gray-500 mt-2">Withdrawal cap: ${{ number_format($package['withdrawal_cap'], 2) }}</p>
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

                        <!-- Wallet Binding -->
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">Primary Withdrawal Wallet (BEP20)</h4>
                            @if($user->hasBoundWallet())
                                <p class="text-sm text-gray-700 font-mono break-all">{{ $user->bep20_address }}</p>
                                <p class="text-xs text-gray-500 mt-1">Wallet binding is permanent. Contact support if you need to update your address.</p>
                                <input type="hidden" name="wallet_address" value="{{ $user->bep20_address }}">
                            @else
                                <label class="block text-xs font-medium text-gray-600 mb-2">Enter your BEP20 wallet address</label>
                                <input type="text" name="wallet_address" value="{{ old('wallet_address', $user->bep20_address) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-mono" placeholder="0x...">
                                @error('wallet_address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-2">This address will be locked for all future withdrawals.</p>
                            @endif
                        </div>

                        <!-- Transaction Reference -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Reference (optional)</label>
                            <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="Enter TX hash or payment note">
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
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
