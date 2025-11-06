<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
        <title>@yield('title', 'Dashboard - VideoEarn')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
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
    </style>
</head>
<body class="font-inter bg-gray-50 text-gray-900 antialiased">
    <!-- Sidebar - Hidden on mobile, visible on desktop (lg breakpoint = 1024px) -->
    <div class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg" id="sidebar">
        <div class="flex flex-col w-full">
            <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-play-circle text-blue-600 text-lg"></i>
                    </div>
                    <span class="text-white font-bold text-lg">VideoEarn</span>
                </div>
            </div>
            <nav class="mt-8 flex-1 overflow-y-auto">
                <div class="px-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    <a href="{{ route('videos.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('videos.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-video mr-3"></i>Watch Videos
                    </a>
                    <a href="{{ route('earnings') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('earnings') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-chart-line mr-3"></i>My Earnings
                    </a>
                    <a href="{{ route('withdrawal.history') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('withdrawal.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-money-bill-wave mr-3"></i>Withdrawals
                    </a>
                    <a href="{{ route('referrals') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('referrals') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-users mr-3"></i>Referrals
                    </a>
                    <a href="{{ route('level') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('level') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-trophy mr-3"></i>My Level
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700' }}">
                        <i class="fas fa-user mr-3"></i>Profile
                    </a>
                </div>
            </nav>
            <div class="p-4 bg-gray-50 border-t">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center"><i class="fas fa-user text-white"></i></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Member</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-gray-400 hover:text-red-600"><i class="fas fa-sign-out-alt"></i></button></form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content-wrapper pb-24" style="margin-left: 0;">
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="hidden lg:flex items-center space-x-6">
                    <div class="text-center"><p class="text-sm text-gray-500">Videos Watched</p><p class="text-lg font-semibold text-green-600">@yield('quick-videos', '—')</p></div>
                    <div class="text-center"><p class="text-sm text-gray-500">Total Earnings</p><p class="text-lg font-semibold text-blue-600">@yield('quick-earnings', '—')</p></div>
                    <div class="text-center"><p class="text-sm text-gray-500">Points</p><p class="text-lg font-semibold text-purple-600">@yield('quick-points', '—')</p></div>
                </div>
            </div>
        </header>
        <main class="p-4 sm:p-6">
            @yield('content')
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
                    sidebar.style.display = 'none';
                    sidebar.style.visibility = 'hidden';
                    sidebar.style.width = '0';
                    sidebar.style.position = 'absolute';
                    sidebar.style.left = '-9999px';
                }
                if (bottomNav) {
                    bottomNav.style.display = 'flex';
                    bottomNav.style.visibility = 'visible';
                }
                if (mainContent) {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                }
            } else {
                // Desktop view - show sidebar, hide bottom nav
                if (sidebar) {
                    sidebar.style.display = 'flex';
                    sidebar.style.visibility = 'visible';
                    sidebar.style.width = '16rem';
                    sidebar.style.position = 'fixed';
                    sidebar.style.left = '0';
                }
                if (bottomNav) {
                    bottomNav.style.display = 'none';
                    bottomNav.style.visibility = 'hidden';
                }
                if (mainContent) {
                    mainContent.style.marginLeft = '16rem';
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
    
    @yield('scripts')
</body>
</html>


