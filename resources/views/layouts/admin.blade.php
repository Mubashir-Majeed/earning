<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('title', 'Admin Panel - VideoEarn')</title>
    <meta name="description" content="VideoEarn Admin Panel - Manage users, videos, and earnings">

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
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter bg-gray-50 text-gray-900 antialiased">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" id="sidebar">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-blue-600 text-lg"></i>
                </div>
                <span class="text-white font-bold text-lg">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users Management
                </a>
                
                <a href="{{ route('admin.videos') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.videos') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-video mr-3"></i>
                    Videos Management
                </a>
                
                <a href="{{ route('admin.deposits') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.deposits') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-credit-card mr-3"></i>
                    Deposits
                </a>
                
                <a href="{{ route('admin.withdrawals') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.withdrawals') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-money-bill-wave mr-3"></i>
                    Withdrawals
                </a>
                
                <a href="{{ route('admin.analytics') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Analytics
                </a>
                
                <a href="{{ route('admin.settings') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('admin.settings') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
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
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
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
    <div class="main-content-wrapper ml-64">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200">
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
                    mainContent.style.marginLeft = '16rem';
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
