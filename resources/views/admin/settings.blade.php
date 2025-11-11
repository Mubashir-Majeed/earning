@extends('layouts.admin')

@section('title', 'Settings - Earn Quest')
@section('page-title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Platform Settings</h2>
            <p class="text-gray-600">Configure platform parameters and preferences</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="resetSettings()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-undo mr-2"></i>Reset
            </button>
            <button type="submit" form="settingsForm" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </div>

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- General Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2 text-blue-600"></i>General Settings
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" 
                               name="site_name" 
                               id="site_name" 
                               value="{{ $settings['site_name'] }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="site_email" class="block text-sm font-medium text-gray-700 mb-2">Site Email</label>
                        <input type="email" 
                               name="site_email" 
                               id="site_email" 
                               value="{{ $settings['site_email'] }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Financial Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Financial Settings
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="min_withdrawal" class="block text-sm font-medium text-gray-700 mb-2">Minimum Withdrawal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="min_withdrawal" 
                                   id="min_withdrawal" 
                                   step="0.01"
                                   value="{{ old('min_withdrawal', $settings['min_withdrawal']) }}"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="withdrawal_fee_percent" class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Fee (%)</label>
                        <div class="relative">
                            <input type="number" 
                                   name="withdrawal_fee_percent" 
                                   id="withdrawal_fee_percent" 
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   value="{{ old('withdrawal_fee_percent', $settings['withdrawal_fee_percent']) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="platform_wallet_address" class="block text-sm font-medium text-gray-700 mb-2">Platform Deposit Wallet (BEP20)</label>
                        <input type="text"
                               name="platform_wallet_address"
                               id="platform_wallet_address"
                               value="{{ old('platform_wallet_address', $settings['platform_wallet_address']) }}"
                               placeholder="0x..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono">
                        <p class="mt-1 text-xs text-gray-500">Users will see this address on the deposit screen and it will be locked for editing.</p>
                        @error('platform_wallet_address')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Rewards Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-gift mr-2 text-purple-600"></i>Rewards Settings
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="referral_bonus" class="block text-sm font-medium text-gray-700 mb-2">Referral Bonus ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="referral_bonus" 
                                   id="referral_bonus" 
                                   step="0.01"
                                   value="{{ $settings['referral_bonus'] }}"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Package Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-box-open mr-2 text-indigo-600"></i>Package Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($settings['packages'] as $code => $package)
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <h4 class="text-md font-semibold text-gray-900 mb-2 flex items-center justify-between">
                                <span>{{ $package['name'] }}</span>
                                <span class="text-xs uppercase tracking-wide text-gray-500">{{ $code }}</span>
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">{{ $package['description'] }}</p>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Deposit Amount ($)</label>
                                    <input type="number"
                                           name="packages[{{ $code }}][deposit_amount]"
                                           step="0.01"
                                           min="1"
                                           value="{{ old("packages.$code.deposit_amount", $package['deposit_amount']) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('packages.' . $code . '.deposit_amount')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Withdrawal Cap ($)</label>
                                    <input type="number"
                                           name="packages[{{ $code }}][withdrawal_cap]"
                                           step="0.01"
                                           min="0"
                                           value="{{ old("packages.$code.withdrawal_cap", $package['withdrawal_cap']) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('packages.' . $code . '.withdrawal_cap')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- System Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-server mr-2 text-orange-600"></i>System Settings
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Maintenance Mode</h4>
                            <p class="text-sm text-gray-600">Temporarily disable user access</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Email Notifications</h4>
                            <p class="text-sm text-gray-600">Send email alerts to users</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Auto-approve Deposits</h4>
                            <p class="text-sm text-gray-600">Automatically approve deposits</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-red-200">
        <h3 class="text-lg font-semibold text-red-900 mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
        </h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div>
                    <h4 class="font-medium text-red-900">Clear All Data</h4>
                    <p class="text-sm text-red-700">Permanently delete all user data and transactions</p>
                </div>
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Clear Data
                </button>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div>
                    <h4 class="font-medium text-red-900">Reset Platform</h4>
                    <p class="text-sm text-red-700">Reset all settings to default values</p>
                </div>
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset Platform
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    window.resetSettings = function () {
        if (confirm('Are you sure you want to reset all settings to their original values?')) {
            location.reload();
        }
    };
    
    // Auto-save functionality
    let saveTimeout;
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('change', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // Auto-save could be implemented here
                console.log('Settings changed, auto-save in 2 seconds...');
            }, 2000);
        });
    });
</script>
@endsection
@endsection
