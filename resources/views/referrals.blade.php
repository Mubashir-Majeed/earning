@extends('layouts.user')

@section('title', 'Referrals - VideoEarn')
@section('page-title', 'Referrals')

@section('quick-videos', $user->referrals_count)
@section('quick-earnings', '$' . number_format($user->referrals_count * 5, 2))
@section('quick-points', $user->referrals_count * 100)

@section('content')
    <!-- Referral Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Referrals -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Referrals</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $user->referrals_count }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Referral Earnings -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Referral Earnings</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($user->referrals_count * 5, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Referral Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Referral Points</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $user->referrals_count * 100 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Link Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-share-alt text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Refer & Earn</h2>
                <p class="text-blue-100">Earn $5 for each friend who joins and makes their first deposit!</p>
            </div>
        </div>
        
        <div class="bg-white bg-opacity-10 rounded-lg p-4 mb-4">
            <label class="block text-sm font-medium text-blue-100 mb-2">Your Referral Link</label>
            <div class="flex items-center gap-3">
                <input 
                    type="text" 
                    readonly 
                    id="referralLink"
                    class="flex-1 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg px-4 py-3 text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50" 
                    value="{{ $referralLink }}" 
                />
                <button 
                    onclick="copyReferralLink()" 
                    class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    <i class="fas fa-copy mr-2"></i>Copy Link
                </button>
            </div>
        </div>

        <!-- Referral Benefits -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                <span class="text-sm">You earn $5 when they deposit</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                <span class="text-sm">They get 100 bonus points</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                <span class="text-sm">No limit on referrals</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                <span class="text-sm">Instant earnings</span>
            </div>
        </div>
    </div>

    <!-- Referrals Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Your Referrals</h3>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($referrals as $ref)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $ref->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $ref->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ref->has_deposited)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Deposited
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ref->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No referrals yet</h3>
                                <p class="text-gray-500 mb-4">Share your referral link to start earning!</p>
                                <button onclick="copyReferralLink()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-share mr-2"></i>Share Link
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($referrals->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $referrals->links() }}
        </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function copyReferralLink() {
            const linkInput = document.getElementById('referralLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Show success message
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-white', 'text-blue-600');
                button.classList.add('text-white');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-white', 'text-blue-600');
                    button.classList.remove('text-white');
                }, 2000);
                
            } catch (err) {
                alert('Failed to copy link. Please copy manually.');
            }
        }
    </script>
@endsection


