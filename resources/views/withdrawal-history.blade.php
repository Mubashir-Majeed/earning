@extends('layouts.user')

@section('title', 'Withdrawal History - VideoEarn')
@section('page-title', 'Withdrawal History')

@section('content')
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Withdrawal History</h1>
            <a href="{{ route('withdrawal') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>Request Withdrawal
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b"><h2 class="font-semibold">Your Requests</h2></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($withdrawals as $w)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">${{ number_format($w->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($w->fee_amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($w->net_amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst(str_replace('_',' ', $w->withdrawal_method)) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $w->status==='pending',
                                        'bg-blue-100 text-blue-800' => $w->status==='processing',
                                        'bg-green-100 text-green-800' => $w->status==='completed',
                                        'bg-red-100 text-red-800' => $w->status==='failed',
                                    ])
                                ">{{ ucfirst($w->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $w->requested_at?->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-wallet text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No withdrawals yet</h3>
                                    <p class="text-gray-500 mb-4">You haven't made any withdrawal requests yet.</p>
                                    <a href="{{ route('withdrawal') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Make Your First Withdrawal
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">{{ $withdrawals->links() }}</div>
        </div>
@endsection


