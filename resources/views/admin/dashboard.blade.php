@extends('layouts.admin')

@section('title', 'Admin Dashboard - VideoEarn')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100">Here's what's happening with your VideoEarn platform today.</p>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+12.5% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_users']) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+8.2% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Videos -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Videos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_videos']) }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-plus mr-1"></i>5 new this week
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-video text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Withdrawals</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_withdrawals']) }}</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-clock mr-1"></i>Needs attention
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Total Earnings -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Total Earnings</h3>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600"></i>
                </div>
            </div>
            <p class="text-4xl font-bold text-gray-900 mb-2">${{ number_format($stats['total_earnings'], 2) }}</p>
            <p class="text-sm text-gray-600">All time user earnings</p>
            <div class="mt-4 flex items-center text-green-600">
                <i class="fas fa-arrow-up mr-2"></i>
                <span class="text-sm font-medium">+15.3% this month</span>
            </div>
        </div>

        <!-- Total Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Total Deposits</h3>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-credit-card text-blue-600"></i>
                </div>
            </div>
            <p class="text-4xl font-bold text-gray-900 mb-2">${{ number_format($stats['total_deposits'], 2) }}</p>
            <p class="text-sm text-gray-600">Platform revenue</p>
            <div class="mt-4 flex items-center text-blue-600">
                <i class="fas fa-arrow-up mr-2"></i>
                <span class="text-sm font-medium">+22.1% this month</span>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg">7D</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">30D</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">90D</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-green-100 text-green-600 rounded-lg">7D</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">30D</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">90D</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentUsers ?? [] as $user)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">${{ number_format($user->balance, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No recent users</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Withdrawals</h3>
                    <a href="{{ route('admin.withdrawals') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentWithdrawals ?? [] as $withdrawal)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-orange-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $withdrawal->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $withdrawal->withdrawal_method }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">${{ number_format($withdrawal->amount, 2) }}</p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-money-bill-wave text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No pending withdrawals</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                    <span class="text-blue-900 font-medium">Manage Users</span>
                </a>
                <a href="{{ route('admin.videos') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="fas fa-video text-purple-600 text-xl mr-3"></i>
                    <span class="text-purple-900 font-medium">Manage Videos</span>
                </a>
                <a href="{{ route('admin.withdrawals') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                    <i class="fas fa-money-bill-wave text-orange-600 text-xl mr-3"></i>
                    <span class="text-orange-900 font-medium">Process Withdrawals</span>
                </a>
                <a href="#" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-chart-bar text-green-600 text-xl mr-3"></i>
                    <span class="text-green-900 font-medium">View Analytics</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Revenue ($)',
                data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // User Growth Chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'New Users',
                data: [150, 200, 180, 300, 250, 400, 350],
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection