<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('title', 'Admin Panel - Earn Quest')</title>
    <meta name="description" content="Earn Quest Admin Panel - Manage users, videos, and earnings">

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
                padding-bottom: 5rem !important;
            }
            body #sidebar-overlay {
                display: none !important;
            }
        }
        @media screen and (min-width: 1024px) {
            body #sidebar,
            html body #sidebar {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 18rem !important;
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
                margin-left: 18rem !important;
                padding-bottom: 0 !important;
            }
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

        body {
            background: linear-gradient(125deg, #eef2ff 0%, #f8fafc 38%, #e0e7ff 100%);
        }

        .main-content-wrapper {
            transition: margin-left 0.3s ease, width 0.3s ease;
            min-height: 100vh;
        }

        .sidebar-container {
            position: relative;
            height: 100%;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.12), transparent 55%),
                        linear-gradient(185deg, #0f172a 0%, #111827 55%, #020617 100%);
            color: #e2e8f0;
            border-right: 1px solid rgba(148,163,184,0.18);
            box-shadow: 12px 0 32px -28px rgba(15,23,42,0.85);
        }

        .sidebar-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(140deg, rgba(59,130,246,0.22), rgba(129,140,248,0.18));
            opacity: 0.35;
            pointer-events: none;
        }

        .sidebar-container > * {
            position: relative;
            z-index: 1;
        }

        .sidebar-header {
            background: linear-gradient(135deg, rgba(59,130,246,0.25), rgba(79,70,229,0.22));
            border-bottom: 1px solid rgba(148,163,184,0.12);
        }

        .sidebar-nav {
            max-height: calc(100vh - 240px);
            overflow-y: auto;
            padding-bottom: 2rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(148,163,184,0.35) transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,0.35);
            border-radius: 999px;
        }

        .sidebar-title {
            letter-spacing: 0.28em;
            font-weight: 600;
            color: rgba(226,232,240,0.55);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.85rem 1.05rem;
            border-radius: 1rem;
            font-weight: 500;
            color: rgba(226,232,240,0.8);
            transition: all 0.25s ease;
            position: relative;
        }

        .sidebar-link:hover {
            background: linear-gradient(110deg, rgba(59,130,246,0.22), rgba(129,140,248,0.22));
            color: #ffffff;
            transform: translateX(3px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(59,130,246,0.35), rgba(109,40,217,0.32));
            color: #ffffff;
            box-shadow: 0 18px 36px -24px rgba(59,130,246,0.85);
        }

        .sidebar-link .sidebar-icon {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(148,163,184,0.25);
            color: inherit;
            transition: all 0.25s ease;
        }

        .sidebar-link.active .sidebar-icon {
            background: linear-gradient(135deg, rgba(59,130,246,0.95), rgba(129,140,248,0.95));
            color: #ffffff;
            box-shadow: 0 14px 28px -16px rgba(79,70,229,0.75);
        }

        .sidebar-link:hover .sidebar-icon {
            background: linear-gradient(135deg, rgba(59,130,246,0.6), rgba(129,140,248,0.6));
            color: #ffffff;
        }

        .sidebar-link::after {
            content: '';
            position: absolute;
            right: 0.55rem;
            top: 50%;
            width: 0.35rem;
            height: 0.35rem;
            background: rgba(148,163,184,0.35);
            border-radius: 9999px;
            transform: translateY(-50%);
            transition: background 0.25s ease;
        }

        .sidebar-link.active::after {
            background: #60a5fa;
        }

        .sidebar-user-card {
            background: rgba(255,255,255,0.92);
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 16px 40px -28px rgba(15,23,42,0.7);
            border: 1px solid rgba(148,163,184,0.14);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter text-gray-900 antialiased">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-72 transform transition-transform duration-300 ease-in-out" id="sidebar" style="top: 0;">
        <div class="sidebar-container h-full flex flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-between h-20 px-6 sidebar-header">
                <div class="flex items-center space-x-4">
                    <div class="w-11 h-11 bg-white/15 rounded-2xl flex items-center justify-center shadow-inner border border-white/20">
                        <i class="fas fa-shield-heart text-white text-lg"></i>
                    </div>
                    <div class="leading-tight">
                        <span class="block text-white font-semibold text-lg tracking-[0.28em] uppercase">Earn Quest</span>
                    </div>
                </div>
                <span class="hidden lg:inline-flex items-center px-3 py-1 text-[10px] font-semibold text-white/80 bg-white/10 rounded-full backdrop-blur-sm border border-white/20">Secure Session</span>
            </div>

            @php
                $sidebarLinks = [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'fa-gauge-high',
                        'route' => 'admin.dashboard',
                        'active' => ['admin.dashboard'],
                    ],
                    [
                        'label' => 'Users Management',
                        'icon' => 'fa-users',
                        'route' => 'admin.users',
                        'active' => ['admin.users', 'admin.users.*'],
                    ],
                    [
                        'label' => 'Videos Management',
                        'icon' => 'fa-video',
                        'route' => 'admin.videos',
                        'active' => ['admin.videos', 'admin.videos.*'],
                    ],
                    [
                        'label' => 'Deposits',
                        'icon' => 'fa-coins',
                        'route' => 'admin.deposits',
                        'active' => ['admin.deposits', 'admin.deposits.*'],
                    ],
                    [
                        'label' => 'Withdrawals',
                        'icon' => 'fa-money-bill-transfer',
                        'route' => 'admin.withdrawals',
                        'active' => ['admin.withdrawals', 'admin.withdrawals.*'],
                    ],
                    [
                        'label' => 'Referrals',
                        'icon' => 'fa-user-group',
                        'route' => 'admin.referrals',
                        'active' => ['admin.referrals'],
                    ],
                    [
                        'label' => 'Analytics',
                        'icon' => 'fa-chart-line',
                        'route' => 'admin.analytics',
                        'active' => ['admin.analytics'],
                    ],
                    [
                        'label' => 'Settings',
                        'icon' => 'fa-gear',
                        'route' => 'admin.settings',
                        'active' => ['admin.settings'],
                    ],
                ];
            @endphp

            <nav class="mt-4 px-5 sidebar-nav">
                <p class="text-xs text-white/40 uppercase sidebar-title mb-4">Navigation</p>

                @foreach($sidebarLinks as $link)
                    @php
                        $isActive = request()->routeIs($link['active']);
                    @endphp
                    <a href="{{ route($link['route']) }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <i class="fas {{ $link['icon'] }}"></i>
                        </span>
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <!-- User Info -->
            <div class="px-5 pb-6 mt-auto">
                <div class="sidebar-user-card">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">Administrator</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition" title="Logout">
                                <i class="fas fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content-wrapper ml-72" style="padding-top: 1.5rem;">
        <!-- Top Bar -->
        <header class="bg-white/80 backdrop-blur-md border-b border-white/60 shadow-lg">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
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
                            <p class="text-sm text-gray-500">Active Users</p>
                            <p class="text-lg font-semibold text-green-600">{{ isset($stats) ? number_format($stats['active_users']) : '—' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Pending Withdrawals</p>
                            <p class="text-lg font-semibold text-orange-600">{{ isset($stats) ? number_format($stats['pending_withdrawals']) : '—' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Today's Revenue</p>
                            <p class="text-lg font-semibold text-blue-600">{{ isset($stats) ? ('$'.number_format($stats['today_revenue'], 2)) : '—' }}</p>
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

            @yield('content')
        </main>
    </div>

    @yield('scripts')

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-2xl" style="display: none;">
        <div class="flex items-center h-20 w-full safe-area-bottom" style="gap: 0; margin: 0; padding: 0;">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-tachometer-alt text-xl {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500' }}">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.users') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('admin.users') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('admin.users'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-users text-xl {{ request()->routeIs('admin.users') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('admin.users') ? 'text-blue-600' : 'text-gray-500' }}">Users</span>
            </a>
            
            <a href="{{ route('admin.videos') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('admin.videos') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('admin.videos'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.videos') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-video text-xl {{ request()->routeIs('admin.videos') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('admin.videos') ? 'text-blue-600' : 'text-gray-500' }}">Videos</span>
            </a>
            
            <a href="{{ route('admin.deposits') }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ request()->routeIs('admin.deposits') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                <div class="relative">
                    @if(request()->routeIs('admin.deposits'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.deposits') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                        <i class="fas fa-credit-card text-xl {{ request()->routeIs('admin.deposits') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    </div>
                </div>
                <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('admin.deposits') ? 'text-blue-600' : 'text-gray-500' }}">Deposits</span>
            </a>
            
            <div class="relative flex flex-col items-center justify-center flex-1 h-full min-w-0">
                <button id="admin-mobile-menu-button" class="flex flex-col items-center justify-center relative group transition-all duration-200 {{ request()->routeIs('admin.withdrawals') || request()->routeIs('admin.analytics') || request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
                    <div class="relative">
                        @if(request()->routeIs('admin.withdrawals') || request()->routeIs('admin.analytics') || request()->routeIs('admin.settings'))
                            <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                        @endif
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.withdrawals') || request()->routeIs('admin.analytics') || request()->routeIs('admin.settings') ? 'bg-blue-50' : 'group-hover:bg-gray-50' }}">
                            <i class="fas fa-ellipsis-h text-xl {{ request()->routeIs('admin.withdrawals') || request()->routeIs('admin.analytics') || request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold mt-1 {{ request()->routeIs('admin.withdrawals') || request()->routeIs('admin.analytics') || request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-500' }}">More</span>
                </button>
                <!-- Dropdown Menu -->
                <div id="admin-mobile-dropdown" class="hidden absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden" style="max-height: calc(100vh - 120px); overflow-y: auto; bottom: calc(100% + 12px);">
                    <div class="py-2">
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center px-5 py-3.5 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('admin.withdrawals') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700' : '' }}">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center mr-3">
                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            <span class="font-medium">Withdrawals</span>
                        </a>
                        <a href="{{ route('admin.analytics') }}" class="flex items-center px-5 py-3.5 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700' : '' }}">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center mr-3">
                                <i class="fas fa-chart-bar text-white text-sm"></i>
                            </div>
                            <span class="font-medium">Analytics</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center px-5 py-3.5 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700' : '' }}">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center mr-3">
                                <i class="fas fa-cog text-white text-sm"></i>
                            </div>
                            <span class="font-medium">Settings</span>
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
        // Immediate inline styles to prevent FOUC
        (function() {
            if (window.innerWidth < 1024) {
                const sidebar = document.getElementById('sidebar');
                const bottomNav = document.getElementById('bottom-nav');
                const mainContent = document.querySelector('.main-content-wrapper');
                
                if (sidebar) {
                    sidebar.style.display = 'none';
                    sidebar.style.visibility = 'hidden';
                }
                if (bottomNav) {
                    bottomNav.style.display = 'flex';
                    bottomNav.style.visibility = 'visible';
                }
                if (mainContent) {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.paddingBottom = '5rem';
                }
            } else {
                const sidebar = document.getElementById('sidebar');
                const bottomNav = document.getElementById('bottom-nav');
                const mainContent = document.querySelector('.main-content-wrapper');
                
                if (sidebar) {
                    sidebar.style.display = 'flex';
                    sidebar.style.visibility = 'visible';
                }
                if (bottomNav) {
                    bottomNav.style.display = 'none';
                    bottomNav.style.visibility = 'hidden';
                }
                if (mainContent) {
                    mainContent.style.marginLeft = '18rem';
                    mainContent.style.paddingBottom = '0';
                }
            }
        })();

        // Admin mobile menu toggle with smart positioning
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('admin-mobile-menu-button');
            const dropdown = document.getElementById('admin-mobile-dropdown');
            
            if (menuButton && dropdown) {
                menuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
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
                            const dropdownHeight = rect.height || 250; // Approximate height
                            
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
                    if (!menuButton.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
