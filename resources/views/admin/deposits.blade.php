@extends('layouts.admin')

@section('title', 'Deposits Management - Earn Quest')
@section('page-title', 'Deposits Management')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $packageCatalog = $packages ?? config('investment.packages');
@endphp

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Deposit Management</h2>
            <p class="text-gray-600">Review and manage user deposits</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.deposits') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Users / Transaction ID</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name, email, or transaction ID..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount Range</label>
                <select name="amount_range" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Amounts</option>
                    <option value="35" {{ request('amount_range') == '35' ? 'selected' : '' }}>$35</option>
                    <option value="50" {{ request('amount_range') == '50' ? 'selected' : '' }}>$50</option>
                    <option value="100" {{ request('amount_range') == '100' ? 'selected' : '' }}>$100</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'amount_range']))
                    <a href="{{ route('admin.deposits') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Deposits Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($deposits as $deposit)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $deposit->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $deposit->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($deposit->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ data_get($packageCatalog, $deposit->package_code.'.name', '—') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                @php
                                    // Try to get wallet address from deposit payment_details, user's bep20_address, or user's payment_details
                                    $walletAddress = $deposit->payment_details 
                                        ?? $deposit->user->bep20_address 
                                        ?? $deposit->user->payment_details
                                        ?? null;
                                @endphp
                                @if($walletAddress)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono" title="{{ $walletAddress }}">{{ Str::limit($walletAddress, 12, '...') }}</span>
                                        <button type="button" class="text-blue-600 hover:text-blue-700 transition-colors" onclick="copyToClipboard('{{ $walletAddress }}', this)" title="Copy address">
                                            <i class="fas fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ strtoupper($deposit->payment_method) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @php
                                    $reference = $deposit->payment_id;
                                    if (!$reference && $deposit->notes) {
                                        $reference = Str::after($deposit->notes, 'TX Reference: ');
                                        $reference = trim($reference);
                                    }
                                    $reference = $reference ?: null;
                                    $receiptUrl = null;
                                    if ($deposit->receipt_path) {
                                        $path = ltrim($deposit->receipt_path, '/');
                                        // Handle both old storage paths and new public paths
                                        if (strpos($path, 'images/') === 0) {
                                            $receiptUrl = asset('public/' . $path);
                                        } else {
                                            $receiptUrl = asset('storage/' . $path);
                                        }
                                    }
                                @endphp
                                <div class="flex flex-col space-y-1">
                                    <span class="font-mono text-gray-800">{{ $reference ?? '—' }}</span>
                                    @if($receiptUrl)
                                        <a href="{{ $receiptUrl }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700">
                                            <i class="fas fa-file-image mr-1"></i>View receipt
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($deposit->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                @elseif($deposit->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $deposit->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    @if($deposit->status === 'pending')
                                        <form method="POST" action="{{ route('admin.deposits.complete', $deposit) }}">
                                            @csrf
                                            <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Mark Completed</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.deposits.fail', $deposit) }}" data-fail-form id="fail-form-{{ $deposit->id }}">
                                            @csrf
                                            <input type="hidden" name="failure_reason">
                                            <button type="button" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700" data-fail-trigger data-form="fail-form-{{ $deposit->id }}">Mark Failed</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if($deposit->status === 'failed' && $deposit->notes)
                            <tr class="bg-red-50">
                                <td colspan="9" class="px-6 py-3 text-sm text-red-700">
                                    <i class="fas fa-exclamation-circle mr-2"></i><span class="font-semibold">Rejection Reason:</span> {{ $deposit->notes }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-credit-card text-4xl mb-4"></i>
                                    <p class="text-lg">No deposits found</p>
                                    <p class="text-sm">No deposit records match your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deposits->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $deposits->links() }}
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Deposits</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $deposits->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Completed Amount</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($deposits->where('status', 'completed')->sum('amount'), 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $deposits->where('status', 'completed')->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Processed</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($deposits->whereIn('status', ['completed','failed'])->sum('amount'), 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Reason Modal -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Reject Deposit</h3>
                    <p class="text-sm text-slate-500">Share the reason for rejection. The member will see this message on their dashboard.</p>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-600" data-reject-close>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div>
                <label for="rejectReasonInput" class="block text-sm font-medium text-slate-700 mb-2">Rejection Reason</label>
                <textarea id="rejectReasonInput" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Example: Payment proof does not match the transfer amount."></textarea>
                <p class="text-xs text-slate-500 mt-2">Provide clear guidance so the member can re-submit accurately.</p>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300" data-reject-close>Cancel</button>
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" data-reject-submit>Reject Deposit</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function copyToClipboard(text, button) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                const icon = button.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'fas fa-check text-xs text-green-600';
                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            }).catch(() => {
                alert('Failed to copy');
            });
        } else {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            const icon = button.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-check text-xs text-green-600';
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const triggers = document.querySelectorAll('[data-fail-trigger]');
        if (!triggers.length) {
            return;
        }

        const modal = document.getElementById('rejectModal');
        const reasonInput = document.getElementById('rejectReasonInput');
        const closeButtons = modal.querySelectorAll('[data-reject-close]');
        const submitButton = modal.querySelector('[data-reject-submit]');
        let activeForm = null;

        function openModal(form) {
            activeForm = form;
            reasonInput.value = '';
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            activeForm = null;
        }

        triggers.forEach(button => {
            button.addEventListener('click', () => {
                const formId = button.getAttribute('data-form');
                if (!formId) {
                    return;
                }
                const form = document.getElementById(formId);
                if (!form) {
                    return;
                }
                openModal(form);
            });
        });

        closeButtons.forEach(btn => btn.addEventListener('click', closeModal));

        submitButton.addEventListener('click', () => {
            if (!activeForm) {
                return;
            }
            const reason = reasonInput.value.trim();
            if (!reason) {
                alert('Please enter a rejection reason.');
                return;
            }

            const formToSubmit = activeForm;
            formToSubmit.querySelector('input[name="failure_reason"]').value = reason;
            closeModal();
            formToSubmit.submit();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
</script>
@endsection
@endsection
 
 
