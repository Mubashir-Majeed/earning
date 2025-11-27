<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Dashboard - Earn Quest')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(120deg, #e0e7ff 0%, #f1f5f9 40%, #e2e8f0 100%);
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        .main-content-wrapper {
            transition: margin-left 0.3s ease, width 0.3s ease;
            min-height: 100vh;
            margin-left: 0;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        .sidebar-container {
            position: relative;
            height: 100%;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.15), transparent 55%),
                        linear-gradient(200deg, #1f2937 0%, #0f172a 55%, #090d16 100%);
            color: rgba(226,232,240,0.85);
            border-right: 1px solid rgba(148,163,184,0.18);
            box-shadow: 10px 0 30px -28px rgba(15,23,42,0.85);
        }

        .sidebar-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(140deg, rgba(59,130,246,0.22), rgba(79,70,229,0.18));
            opacity: 0.35;
            pointer-events: none;
        }

        .sidebar-container > * {
            position: relative;
            z-index: 1;
        }

        .sidebar-header {
            background: linear-gradient(135deg, rgba(59,130,246,0.28), rgba(129,140,248,0.25));
            border-bottom: 1px solid rgba(148,163,184,0.12);
        }

        .sidebar-nav {
            max-height: calc(100vh - 240px);
            overflow-y: auto;
            padding-bottom: 2rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(148,163,184,0.35) transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,0.35);
            border-radius: 999px;
        }

        .sidebar-title {
            letter-spacing: 0.3em;
            font-weight: 600;
            color: rgba(226,232,240,0.55);
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1.05rem;
            border-radius: 1rem;
            font-weight: 500;
            color: rgba(226,232,240,0.75);
            transition: all 0.25s ease;
            position: relative;
        }
        .sidebar-link:hover {
            background: linear-gradient(120deg, rgba(59,130,246,0.20), rgba(129,140,248,0.20));
            color: #ffffff;
            transform: translateX(3px);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(59,130,246,0.35), rgba(109,40,217,0.3));
            color: #ffffff;
            box-shadow: 0 16px 32px -24px rgba(59,130,246,0.85);
        }

        .sidebar-icon {
            width: 2.3rem;
            height: 2.3rem;
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
            box-shadow: 0 14px 30px -18px rgba(79,70,229,0.75);
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
        .sidebar-link.active::after { background: #60a5fa; }

        .sidebar-profile {
            background: rgba(255,255,255,0.92);
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid rgba(148,163,184,0.12);
            box-shadow: 0 16px 34px -28px rgba(15,23,42,0.65);
        }

        #sidebar {
            display: none;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.15), transparent 55%),
                        linear-gradient(200deg, #1f2937 0%, #0f172a 55%, #090d16 100%);
            border-right: 1px solid rgba(148,163,184,0.18);
            box-shadow: 10px 0 30px -28px rgba(15,23,42,0.85);
        }
        #bottom-nav {
            display: flex;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(15,23,42,0.92);
        }

        header.user-header {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(241,245,249,0.7);
            box-shadow: 0 20px 40px -24px rgba(15,23,42,0.25);
        }

        @media (min-width: 1024px) {
            body #sidebar { display: flex !important; width: 18rem !important; }
            body #bottom-nav { display: none !important; }
            body .main-content-wrapper { margin-left: 18rem !important; width: calc(100% - 18rem); max-width: calc(100vw - 18rem); }
        }
        
        /* Zoom handling */
        * {
            box-sizing: border-box;
        }
        
        .container, .max-w-7xl, .max-w-2xl, .max-w-md {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        /* Prevent horizontal overflow */
        section, div[class*="grid"], div[class*="flex"] {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        /* Text wrapping */
        h1, h2, h3, h4, h5, h6, p, span {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body class="font-inter text-gray-900 antialiased">
    <div class="fixed inset-y-0 left-0 z-50 w-72 transform transition-transform duration-300 ease-in-out" id="sidebar">
        <div class="sidebar-container h-full flex flex-col">
            <div class="sidebar-header flex items-center justify-between h-20 px-6">
                <div class="flex items-center space-x-4">
                    <div class="w-11 h-11 bg-white/15 rounded-2xl flex items-center justify-center shadow-inner border border-white/20">
                        <i class="fas fa-shield-heart text-white text-lg"></i>
                    </div>
                    <div class="leading-tight">
                        <span class="block text-white font-semibold text-lg tracking-[0.28em] uppercase">Earn Quest</span>
                    </div>
                </div>
                <span class="hidden lg:inline-flex items-center px-3 py-1 text-[10px] font-semibold text-white/80 bg-white/10 rounded-full backdrop-blur-sm border border-white/20">Member Access</span>
            </div>

            @php
                $userLinks = [
                    ['label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => 'dashboard', 'active' => ['dashboard']],
                    ['label' => 'Watch Videos', 'icon' => 'fa-video', 'route' => 'videos.index', 'active' => ['videos.*']],
                    ['label' => 'Activate Package', 'icon' => 'fa-gem', 'route' => 'deposit', 'active' => ['deposit']],
                    ['label' => 'My Earnings', 'icon' => 'fa-coins', 'route' => 'earnings', 'active' => ['earnings']],
                    ['label' => 'Withdrawals', 'icon' => 'fa-wallet', 'route' => 'withdrawal', 'active' => ['withdrawal']],
                    ['label' => 'Withdrawal History', 'icon' => 'fa-clock-rotate-left', 'route' => 'withdrawal.history', 'active' => ['withdrawal.history']],
                    ['label' => 'Referrals', 'icon' => 'fa-user-group', 'route' => 'referrals', 'active' => ['referrals']],
                    ['label' => 'My Level', 'icon' => 'fa-trophy', 'route' => 'level', 'active' => ['level']],
                    ['label' => 'Profile', 'icon' => 'fa-user', 'route' => 'profile.edit', 'active' => ['profile.*']],
                ];
            @endphp

            <nav class="mt-4 px-5 sidebar-nav">
                <p class="text-xs sidebar-title mb-4">Navigation</p>
                @foreach($userLinks as $link)
                    @php $active = request()->routeIs($link['active']); @endphp
                    <a href="{{ route($link['route']) }}" class="sidebar-link {{ $active ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <i class="fas {{ $link['icon'] }}"></i>
                        </span>
                        <span class="relative">
                            {{ $link['label'] }}
                            @if($active)
                                <span class="absolute -right-6 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-white/60"></span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </nav>

            <div class="px-5 pb-6 mt-auto">
                <div class="sidebar-profile">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-sm">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">Member</p>
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

    <div class="main-content-wrapper pb-24 lg:ml-72" style="padding-top: 1.5rem;">
        <header class="user-header sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-white/60 shadow-lg max-w-full overflow-x-hidden">
            <div class="flex items-center justify-between px-3 sm:px-4 lg:px-6 py-3 sm:py-4 gap-2 sm:gap-4">
                <div class="flex items-center space-x-2 sm:space-x-4 min-w-0 flex-1">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-800 truncate">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                    <button class="relative p-1.5 sm:p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                        <i class="fas fa-bell text-base sm:text-lg lg:text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-red-500 text-white text-[10px] sm:text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                    <div class="hidden md:flex items-center space-x-3 sm:space-x-4 lg:space-x-6">
                        <div class="text-center min-w-0">
                            <p class="text-xs sm:text-sm text-slate-500 truncate">Videos Watched</p>
                            <p class="text-sm sm:text-base lg:text-lg font-semibold text-green-500 truncate">@yield('quick-videos', '—')</p>
                        </div>
                        <div class="text-center min-w-0">
                            <p class="text-xs sm:text-sm text-slate-500 truncate">Total Earnings</p>
                            <p class="text-sm sm:text-base lg:text-lg font-semibold text-blue-600 truncate">@yield('quick-earnings', '—')</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="p-3 sm:p-4 lg:p-6 max-w-full overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-50 border-t border-white/20 shadow-2xl">
        <div class="flex items-center justify-center h-16 w-full safe-area-bottom px-2 gap-0">
            @php
                $bottomLinks = [
                    ['label' => 'Dashboard', 'icon' => 'fa-home', 'route' => 'dashboard', 'active' => ['dashboard']],
                    ['label' => 'Videos', 'icon' => 'fa-video', 'route' => 'videos.index', 'active' => ['videos.*']],
                    ['label' => 'Package', 'icon' => 'fa-gem', 'route' => 'deposit', 'active' => ['deposit']],
                    ['label' => 'Earnings', 'icon' => 'fa-chart-line', 'route' => 'earnings', 'active' => ['earnings']],
                    ['label' => 'Withdraw', 'icon' => 'fa-wallet', 'route' => 'withdrawal', 'active' => ['withdrawal']],
                    ['label' => 'Referrals', 'icon' => 'fa-user-group', 'route' => 'referrals', 'active' => ['referrals']],
                ];
            @endphp

            @foreach($bottomLinks as $link)
                @php $active = request()->routeIs($link['active']); @endphp
                <a href="{{ route($link['route']) }}" class="flex flex-col items-center justify-center flex-1 h-full min-w-0 relative group transition-all duration-200 {{ $active ? 'text-blue-200' : 'text-slate-300 hover:text-blue-200' }}">
                    <div class="relative">
                        @if($active)
                            <div class="absolute -top-1 right-0 w-full h-full rounded-2xl border border-blue-300/40 bg-blue-500/10"></div>
                        @endif
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center border transition-all duration-200 {{ $active ? 'bg-blue-500/15 border-blue-300/40 text-blue-200 shadow-lg shadow-blue-500/20' : 'border-transparent group-hover:bg-white/10' }}">
                            <i class="fas {{ $link['icon'] }} text-lg"></i>
                        </div>
                    </div>
                    <span class="hidden text-[10px] font-semibold mt-1 tracking-[0.2em] uppercase {{ $active ? 'text-blue-200' : 'text-slate-300' }}">{{ $link['label'] }}</span>
                </a>
            @endforeach

            <div class="relative flex flex-col items-center justify-center flex-1 h-full min-w-0 z-10">
                <button id="mobile-menu-button" type="button" class="flex flex-col items-center justify-center relative group transition-all duration-200 {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'text-blue-200' : 'text-slate-300 hover:text-blue-200' }}">
                    <div class="relative">
                        @if(request()->routeIs('level') || request()->routeIs('profile.*'))
                            <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-200 rounded-full"></div>
                        @endif
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center border transition-all duration-200 {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'bg-blue-500/15 border-blue-300/40 text-blue-200' : 'border-transparent group-hover:bg-white/10' }}">
                            <i class="fas fa-ellipsis-h text-lg"></i>
                        </div>
                    </div>
                    <span class="hidden text-[10px] font-semibold mt-1 tracking-[0.2em] uppercase {{ request()->routeIs('level') || request()->routeIs('profile.*') ? 'text-blue-200' : 'text-slate-300' }}">More</span>
                </button>
                <div id="mobile-dropdown" class="hidden fixed w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden" style="bottom: 80px; right: 10px; z-index: 9999; max-height: calc(100vh - 200px); overflow-y: auto;">
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
        // Mobile dropdown toggle - fixed positioning above bottom nav
        (function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const dropdown = document.getElementById('mobile-dropdown');
            
            if (menuButton && dropdown) {
                // Toggle dropdown on button click
                menuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    
                    console.log('Menu button clicked');
                    const isHidden = dropdown.classList.contains('hidden');
                    
                    if (isHidden) {
                        // Show dropdown - positioned above bottom nav
                        dropdown.classList.remove('hidden');
                        console.log('Dropdown shown');
                    } else {
                        // Hide dropdown
                        dropdown.classList.add('hidden');
                        console.log('Dropdown hidden');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (dropdown && !dropdown.classList.contains('hidden')) {
                        if (!dropdown.contains(e.target) && !menuButton.contains(e.target)) {
                            dropdown.classList.add('hidden');
                            console.log('Dropdown closed by outside click');
                        }
                    }
                });
            } else {
                console.error('Menu button or dropdown not found', { menuButton, dropdown });
            }
        })();
    </script>
    
    @yield('scripts')
</body>
</html>


