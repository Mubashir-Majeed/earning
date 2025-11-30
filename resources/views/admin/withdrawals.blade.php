@extends('layouts.admin')

@section('title', 'Withdrawals Management - Earn Quest')
@section('page-title', 'Withdrawals Management')

@section('content')
@php
    $packageCatalog = config('investment.packages');
@endphp

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Withdrawal Management</h2>
            <p class="text-gray-600">Process and manage user withdrawal requests</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-check-circle mr-2"></i>Bulk Approve
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                <input type="text" placeholder="Search by user name or email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount Range</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Amounts</option>
                    <option value="0-50">$0 - $50</option>
                    <option value="50-100">$50 - $100</option>
                    <option value="100-500">$100 - $500</option>
                    <option value="500+">$500+</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Package
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wallet
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fee
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Net Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Method
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Requested
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $withdrawal->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $withdrawal->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($withdrawal->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ data_get($packageCatalog, $withdrawal->user->investment_package.'.name', '—') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                {{ $withdrawal->withdrawal_details }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($withdrawal->fee_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-green-600">${{ number_format($withdrawal->net_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-wallet text-blue-600 mr-2"></i>
                                    <span class="text-sm text-gray-900">BEP20</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($withdrawal->status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($withdrawal->status === 'processing')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Processing
                                    </span>
                                @elseif($withdrawal->status === 'completed')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Completed
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $withdrawal->requested_at ? \Carbon\Carbon::parse($withdrawal->requested_at)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($withdrawal->status === 'pending')
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Approve & Complete">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 transition-colors" title="Mark as Processing">
                                                <i class="fas fa-spinner"></i>
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $withdrawal->id }})" class="text-red-600 hover:text-red-900 transition-colors" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($withdrawal->status === 'processing')
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Complete">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $withdrawal->id }})" class="text-red-600 hover:text-red-900 transition-colors" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button onclick="openViewModal({{ $withdrawal->id }})" class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openNoteModal({{ $withdrawal->id }}, {{ json_encode($withdrawal->admin_notes ?? '') }})" class="text-gray-600 hover:text-gray-900 transition-colors" title="Add Note">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-money-bill-wave text-4xl mb-4"></i>
                                    <p class="text-lg">No withdrawals found</p>
                                    <p class="text-sm">No withdrawal requests match your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($withdrawals->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $withdrawals->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pending Amount</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $withdrawals->where('status', 'completed')->where('completed_at', '>=', today())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Processed</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($withdrawals->where('status', 'completed')->sum('amount'), 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Withdrawal Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Withdrawal</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection</label>
                        <textarea name="admin_notes" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Please provide a reason for rejecting this withdrawal..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Reject Withdrawal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Withdrawal Details</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="viewModalContent" class="space-y-4">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div id="noteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Admin Note</h3>
                <form id="noteForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" id="noteTextarea" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Add notes about this withdrawal..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeNoteModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Save Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Reject Modal Functions
    function openRejectModal(withdrawalId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/withdrawals/${withdrawalId}/reject`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        document.getElementById('rejectForm').reset();
    }

    // View Details Modal Functions
    function openViewModal(withdrawalId) {
        const modal = document.getElementById('viewModal');
        const content = document.getElementById('viewModalContent');
        
        // Show loading
        content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i></div>';
        modal.classList.remove('hidden');
        
        // Fetch withdrawal details
        fetch(`/admin/withdrawals/${withdrawalId}/details`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">User</label>
                            <p class="text-sm font-semibold text-gray-900">${data.user_name}</p>
                            <p class="text-xs text-gray-600">${data.user_email}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Amount</label>
                            <p class="text-sm font-semibold text-gray-900">$${parseFloat(data.amount).toFixed(2)}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Fee</label>
                            <p class="text-sm font-semibold text-gray-900">$${parseFloat(data.fee_amount).toFixed(2)}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Net Amount</label>
                            <p class="text-sm font-semibold text-green-600">$${parseFloat(data.net_amount).toFixed(2)}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="text-sm font-semibold text-gray-900">${data.status}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Method</label>
                            <p class="text-sm font-semibold text-gray-900">${data.method}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-500">Wallet Address</label>
                            <p class="text-sm font-mono text-gray-900 break-all">${data.wallet_address}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Requested At</label>
                            <p class="text-sm text-gray-900">${data.requested_at || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Processed At</label>
                            <p class="text-sm text-gray-900">${data.processed_at || 'N/A'}</p>
                        </div>
                        ${data.admin_notes ? `
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-500">Admin Notes</label>
                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">${data.admin_notes}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = '<div class="text-center py-8 text-red-600">Error loading details</div>';
                console.error('Error:', error);
            });
    }

    function closeViewModal() {
        const modal = document.getElementById('viewModal');
        modal.classList.add('hidden');
    }

    // Note Modal Functions
    function openNoteModal(withdrawalId, currentNote = '') {
        const modal = document.getElementById('noteModal');
        const form = document.getElementById('noteForm');
        const textarea = document.getElementById('noteTextarea');
        
        form.action = `/admin/withdrawals/${withdrawalId}/note`;
        textarea.value = currentNote;
        modal.classList.remove('hidden');
    }

    function closeNoteModal() {
        const modal = document.getElementById('noteModal');
        modal.classList.add('hidden');
        document.getElementById('noteForm').reset();
    }

    // Close modals when clicking outside
    document.getElementById('rejectModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    document.getElementById('viewModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewModal();
        }
    });

    document.getElementById('noteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeNoteModal();
        }
    });
</script>
@endsection