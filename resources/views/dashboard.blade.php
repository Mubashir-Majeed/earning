<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Dashboard - VideoEarn</title>
    <meta name="description" content="VideoEarn User Dashboard - Watch videos and earn money">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        /* Bottom Navigation Bar Styling */
        #bottom-nav {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        #bottom-nav a,
        #bottom-nav button {
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Safe area for devices with notches */
        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
        
        /* Bottom nav buttons - full width, no gaps */
        #bottom-nav > div {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        #bottom-nav a,
        #bottom-nav > div > div {
            flex: 1 1 0%;
            min-width: 0;
            max-width: 100%;
        }
        
        /* Force hide sidebar on mobile, show on desktop - HIGHEST PRIORITY */
        @media screen and (max-width: 1023px) {
            body #sidebar,
            html body #sidebar,
            body > #sidebar {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
                position: absolute !important;
                left: -9999px !important;
                z-index: -1 !important;
            }
            body #bottom-nav,
            html body #bottom-nav,
            body > #bottom-nav {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                z-index: 9999 !important;
            }
            body .main-content-wrapper,
            html body .main-content-wrapper {
                margin-left: 0 !important;
                padding-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        @media screen and (min-width: 1024px) {
            body #sidebar,
            html body #sidebar {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 16rem !important;
                height: auto !important;
                position: fixed !important;
                left: 0 !important;
                z-index: 50 !important;
            }
            body #bottom-nav,
            html body #bottom-nav {
                display: none !important;
                visibility: hidden !important;
            }
            body .main-content-wrapper {
                margin-left: 16rem !important;
            }
        }
        
        /* Default mobile-first: hide sidebar, show bottom nav */
        #sidebar {
            display: none;
        }
        #bottom-nav {
            display: flex;
        }
        .main-content-wrapper {
            margin-left: 0;
            width: 100%;
        }
        
        /* Desktop override */
        @media (min-width: 1024px) {
            #sidebar {
                display: flex;
            }
            #bottom-nav {
                display: none;
            }
            .main-content-wrapper {
                margin-left: 16rem;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter bg-gray-50 text-gray-900 antialiased">
    <!-- Sidebar - Hidden on mobile, visible on desktop -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" id="sidebar" style="display: none !important;">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-play-circle text-blue-600 text-lg"></i>
                </div>
                <span class="text-white font-bold text-lg">VideoEarn</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('videos.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('videos.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-video mr-3"></i>
                    Watch Videos
                </a>
                
                <a href="{{ route('earnings') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('earnings') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-chart-line mr-3"></i>
                    My Earnings
                </a>
                
                <a href="{{ route('withdrawal.history') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('withdrawal.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-money-bill-wave mr-3"></i>
                    Withdrawals
                </a>
                
                <a href="{{ route('referrals') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('referrals') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Referrals
                </a>
                
                <a href="{{ route('level') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('level') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-trophy mr-3"></i>
                    My Level
                </a>
                
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-user mr-3"></i>
                    Profile
                </a>
            </div>
        </nav>

        <!-- User Info -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-50 border-t">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">Member</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content-wrapper ml-64 pb-24 lg:pb-0" style="margin-left: 0;">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">Dashboard</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                    
                    <!-- Quick Stats -->
                    <div class="hidden md:flex items-center space-x-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Videos Watched</p>
                            <p class="text-lg font-semibold text-green-600">{{ $stats['total_videos_watched'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Total Earnings</p>
                            <p class="text-lg font-semibold text-blue-600">${{ number_format($stats['total_earnings'], 2) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Points</p>
                            <p class="text-lg font-semibold text-purple-600">{{ number_format($stats['total_points']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-green-800">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <span class="text-red-800">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                        <i class="fas fa-user-circle text-4xl text-white drop-shadow-lg"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-4xl font-black mb-2 text-white drop-shadow-2xl">Welcome back!</h1>
                                        <p class="text-xl font-bold text-blue-100 drop-shadow-lg">{{ $user->name }}</p>
                                    </div>
                                </div>
                                <p class="text-white text-lg font-semibold drop-shadow-md max-w-2xl">Here's your earning overview and today's tasks. Start watching videos to maximize your earnings!</p>
                            </div>
                            <div class="hidden lg:block">
                                <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-chart-line text-4xl text-white drop-shadow-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Videos Watched Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Videos Watched</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_videos_watched'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-play text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+12% from last week</span>
                    </div>
                </div>

                <!-- Total Earnings Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Earnings</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_earnings'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-blue-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+8% from last week</span>
                    </div>
                </div>

                <!-- Points Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Points Earned</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_points']) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-purple-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+15% from last week</span>
                    </div>
                </div>

                <!-- Today's Earnings Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Today's Earnings</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['today_earnings'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-day text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-orange-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+5% from yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Deposit Alert (if not deposited) -->
            @if(!$user->has_deposited)
            <div class="mb-8">
                <div class="bg-gradient-to-r from-red-500 via-pink-500 to-red-600 rounded-2xl p-8 shadow-2xl relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.3"><circle cx="20" cy="20" r="2"/></g></svg>');"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <h3 class="text-4xl font-black text-white drop-shadow-2xl mr-4">🚨 Deposit Required</h3>
                                <div class="bg-white/20 px-4 py-2 rounded-full">
                                    <span class="text-sm font-bold">URGENT</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-white text-2xl mb-8 font-bold drop-shadow-lg max-w-4xl">Make your $100 deposit to start earning from video tasks and unlock unlimited earning potential. Join thousands of users already earning daily!</p>
                        <div class="flex flex-col sm:flex-row gap-6">
                            <a href="{{ route('deposit') }}" class="group bg-white text-red-600 px-12 py-5 rounded-2xl font-black hover:bg-gray-100 transition-all duration-300 text-center text-xl shadow-2xl hover:shadow-3xl hover:scale-105">
                                <i class="fas fa-credit-card mr-4 group-hover:scale-110 transition-transform"></i>Make $100 Deposit
                            </a>
                            <button class="group bg-white/20 backdrop-blur-sm text-white px-12 py-5 rounded-2xl font-bold hover:bg-white/30 transition-all duration-300 text-xl shadow-xl hover:shadow-2xl hover:scale-105">
                                <i class="fas fa-info-circle mr-4 group-hover:scale-110 transition-transform"></i>Learn More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Success Message for Deposited Users -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-green-600 rounded-2xl p-8 shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm mr-6">
                                    <i class="fas fa-trophy text-4xl text-white drop-shadow-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-white drop-shadow-2xl mb-2">Great Job Today!</h3>
                                    <p class="text-white text-xl font-bold drop-shadow-lg">You've earned ${{ number_format($stats['today_earnings'], 2) }} today. Keep watching videos to earn more!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Requirements Checklist -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Level Requirements Checklist</h3>
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-blue-600"></i>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $user->has_deposited ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                <i class="fas {{ $user->has_deposited ? 'fa-check' : 'fa-times' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Initial Deposit</p>
                                <p class="text-xs text-gray-500">{{ $user->has_deposited ? 'Completed' : 'Required $100' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $user->meetsReferralRequirementForWithdrawal() ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                <i class="fas {{ $user->meetsReferralRequirementForWithdrawal() ? 'fa-check' : 'fa-clock' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Referrals</p>
                                <p class="text-xs text-gray-500">{{ $user->referrals()->count() }} referrals</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $user->withinMonthlyWithdrawalQuota() ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                <i class="fas {{ $user->withinMonthlyWithdrawalQuota() ? 'fa-check' : 'fa-times' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Monthly Quota</p>
                                <p class="text-xs text-gray-500">{{ $user->monthly_withdrawals_count }}/{{ $user->withdrawalMonthlyLimit() }} used</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Quick Actions -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bolt text-blue-600"></i>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('videos.index') }}" class="group bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold mb-2">Watch Videos</h4>
                                        <p class="text-blue-100 text-sm">Start earning by watching videos</p>
                                    </div>
                                    <i class="fas fa-play-circle text-3xl group-hover:scale-110 transition-transform"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('withdrawal.history') }}" class="group bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold mb-2">Withdrawal History</h4>
                                        <p class="text-green-100 text-sm">View withdrawal requests</p>
                                    </div>
                                    <i class="fas fa-history text-3xl group-hover:scale-110 transition-transform"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('earnings') }}" class="group bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold mb-2">View Earnings</h4>
                                        <p class="text-purple-100 text-sm">Track your earnings history</p>
                                    </div>
                                    <i class="fas fa-chart-line text-3xl group-hover:scale-110 transition-transform"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('referrals') }}" class="group bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold mb-2">Refer & Earn</h4>
                                        <p class="text-orange-100 text-sm">Share your referral link</p>
                                    </div>
                                    <i class="fas fa-share-alt text-3xl group-hover:scale-110 transition-transform"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Recent Activity</h3>
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-history text-green-600"></i>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-play text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Watched Video</p>
                                    <p class="text-xs text-gray-500">2 minutes ago</p>
                                </div>
                                <span class="text-sm font-bold text-green-600">+$0.50</span>
                            </div>
                            
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-star text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Earned Points</p>
                                    <p class="text-xs text-gray-500">5 minutes ago</p>
                                </div>
                                <span class="text-sm font-bold text-blue-600">+10 pts</span>
                            </div>
                            
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-gift text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Daily Bonus</p>
                                    <p class="text-xs text-gray-500">1 hour ago</p>
                                </div>
                                <span class="text-sm font-bold text-purple-600">+$2.00</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('level') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View my level →</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bottom Navigation Bar - Mobile Only (hidden on lg screens and above) -->
    <nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-2xl" style="display: flex;">
        <div class="flex items-center h-20 w-full safe-area-bottom" style="gap: 0;">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('dashboard'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-home text-xl {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}">Dashboard</span>
            </a>
            <a href="{{ route('videos.index') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('videos.*') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('videos.*'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('videos.*') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-play-circle text-xl {{ request()->routeIs('videos.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('videos.*') ? 'text-blue-600' : 'text-gray-500' }}">Videos</span>
            </a>
            <a href="{{ route('earnings') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('earnings') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('earnings'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('earnings') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-chart-line text-xl {{ request()->routeIs('earnings') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('earnings') ? 'text-blue-600' : 'text-gray-500' }}">Earnings</span>
            </a>
            <a href="{{ route('withdrawal.history') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('withdrawal.*') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('withdrawal.*'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('withdrawal.*') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-wallet text-xl {{ request()->routeIs('withdrawal.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('withdrawal.*') ? 'text-blue-600' : 'text-gray-500' }}">Withdraw</span>
            </a>
            <a href="{{ route('referrals') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('referrals') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('referrals'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('referrals') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-users text-xl {{ request()->routeIs('referrals') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('referrals') ? 'text-blue-600' : 'text-gray-500' }}">Referrals</span>
            </a>
            <div class="relative flex flex-col items-center justify-center flex-1 h-full min-w-0">
                <button id="mobile-menu-button" class="flex flex-col items-center justify-center relative group transition-all duration-200 {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                    <div class="relative">
                        @if(request()->routeIs('level') || request()->routeIs('profile.*'))
                            <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                        @endif
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                            <i class="fas fa-ellipsis-h text-xl {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-500' }}">More</span>
                </button>
                <!-- Dropdown Menu -->
                <div id="mobile-dropdown" class="hidden absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden" style="max-height: calc(100vh - 120px); overflow-y: auto; bottom: calc(100% + 12px);">
                    <div class="py-2">
                        <a href="{{ route('level') }}" class="flex items-center px-5 py-3.5 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('level') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700' : '' }}">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center mr-3">
                                <i class="fas fa-trophy text-white text-sm"></i>
                            </div>
                            <span class="font-medium">My Level</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-5 py-3.5 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700' : '' }}">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="font-medium">Profile</span>
                        </a>
                    </div>
                    <div class="border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-5 py-3.5 text-red-600 hover:bg-red-50 transition-all duration-200">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-400 to-pink-500 flex items-center justify-center mr-3">
                                    <i class="fas fa-sign-out-alt text-white text-sm"></i>
                                </div>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Run IMMEDIATELY - before page renders
        (function() {
            const width = window.innerWidth || document.documentElement.clientWidth;
            const sidebar = document.getElementById('sidebar');
            const bottomNav = document.getElementById('bottom-nav');
            const mainContent = document.querySelector('.main-content-wrapper');
            
            if (width < 1024) {
                // Mobile - hide sidebar, show bottom nav
                if (sidebar) {
                    sidebar.style.cssText = 'display: none !important; visibility: hidden !important; width: 0 !important; height: 0 !important; position: absolute !important; left: -9999px !important;';
                }
                if (bottomNav) {
                    bottomNav.style.cssText = 'display: flex !important; visibility: visible !important;';
                }
                if (mainContent) {
                    mainContent.style.cssText = 'margin-left: 0 !important; width: 100% !important;';
                }
            } else {
                // Desktop - show sidebar, hide bottom nav
                if (sidebar) {
                    sidebar.style.cssText = 'display: flex !important; visibility: visible !important; width: 16rem !important; position: fixed !important; left: 0 !important;';
                }
                if (bottomNav) {
                    bottomNav.style.cssText = 'display: none !important; visibility: hidden !important;';
                }
                if (mainContent) {
                    mainContent.style.cssText = 'margin-left: 16rem !important;';
                }
            }
        })();
        
        // Ensure sidebar is hidden on mobile and bottom nav is visible
        function checkScreenSize() {
            const sidebar = document.getElementById('sidebar');
            const bottomNav = document.getElementById('bottom-nav');
            const mainContent = document.querySelector('.main-content-wrapper');
            const width = window.innerWidth;
            
            if (width < 1024) {
                // Mobile view - hide sidebar, show bottom nav
                if (sidebar) {
                    sidebar.style.cssText = 'display: none !important; visibility: hidden !important; width: 0 !important; height: 0 !important; position: absolute !important; left: -9999px !important;';
                }
                if (bottomNav) {
                    bottomNav.style.cssText = 'display: flex !important; visibility: visible !important;';
                }
                if (mainContent) {
                    mainContent.style.cssText = 'margin-left: 0 !important; width: 100% !important;';
                }
            } else {
                // Desktop view - show sidebar, hide bottom nav
                if (sidebar) {
                    sidebar.style.cssText = 'display: flex !important; visibility: visible !important; width: 16rem !important; position: fixed !important; left: 0 !important;';
                }
                if (bottomNav) {
                    bottomNav.style.cssText = 'display: none !important; visibility: hidden !important;';
                }
                if (mainContent) {
                    mainContent.style.cssText = 'margin-left: 16rem !important;';
                }
            }
        }
        
        // Run immediately and on load and resize
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', checkScreenSize);
        } else {
            checkScreenSize();
        }
        window.addEventListener('resize', checkScreenSize);
        window.addEventListener('load', checkScreenSize);

        // Mobile dropdown toggle with smart positioning
        document.getElementById('mobile-menu-button')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('mobile-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
                
                if (!dropdown.classList.contains('hidden')) {
                    // Calculate position to keep dropdown within viewport
                    const rect = dropdown.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    const viewportWidth = window.innerWidth;
                    const buttonRect = this.getBoundingClientRect();
                    
                    // Reset positioning
                    dropdown.style.bottom = '';
                    dropdown.style.top = '';
                    dropdown.style.left = '';
                    dropdown.style.transform = '';
                    
                    // Check if dropdown goes off top of screen
                    const spaceAbove = buttonRect.top;
                    const dropdownHeight = rect.height || 200; // Approximate height
                    
                    if (spaceAbove < dropdownHeight + 20) {
                        // Not enough space above, position below button
                        dropdown.style.top = 'calc(100% + 12px)';
                        dropdown.style.bottom = 'auto';
                    } else {
                        // Enough space above, position above button
                        dropdown.style.bottom = 'calc(100% + 12px)';
                        dropdown.style.top = 'auto';
                    }
                    
                    // Ensure dropdown doesn't go off left/right edges
                    const dropdownWidth = rect.width || 224; // 56 * 4 = 224px (w-56)
                    const leftPosition = buttonRect.left + (buttonRect.width / 2) - (dropdownWidth / 2);
                    
                    if (leftPosition < 10) {
                        dropdown.style.left = '10px';
                        dropdown.style.transform = 'none';
                    } else if (leftPosition + dropdownWidth > viewportWidth - 10) {
                        dropdown.style.left = 'auto';
                        dropdown.style.right = '10px';
                        dropdown.style.transform = 'none';
                    } else {
                        dropdown.style.left = '50%';
                        dropdown.style.transform = 'translateX(-50%)';
                    }
                    
                    // Ensure max height doesn't exceed viewport
                    const maxHeight = Math.min(dropdownHeight, viewportHeight - 120);
                    dropdown.style.maxHeight = maxHeight + 'px';
                }
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('mobile-dropdown');
            const button = document.getElementById('mobile-menu-button');
            if (dropdown && !dropdown.contains(e.target) && !button?.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>