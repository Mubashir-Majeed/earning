<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Dashboard - VideoEarn')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-inter bg-gray-50 text-gray-900 antialiased">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg" id="sidebar">
        <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-play-circle text-blue-600 text-lg"></i>
                </div>
                <span class="text-white font-bold text-lg">VideoEarn</span>
            </div>
        </div>
        <nav class="mt-8">
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
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-50 border-t">
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

    <!-- Main Content -->
    <div class="ml-64">
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden"><i class="fas fa-bars text-xl"></i></button>
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <div class="text-center"><p class="text-sm text-gray-500">Videos Watched</p><p class="text-lg font-semibold text-green-600">@yield('quick-videos', '—')</p></div>
                    <div class="text-center"><p class="text-sm text-gray-500">Total Earnings</p><p class="text-lg font-semibold text-blue-600">@yield('quick-earnings', '—')</p></div>
                    <div class="text-center"><p class="text-sm text-gray-500">Points</p><p class="text-lg font-semibold text-purple-600">@yield('quick-points', '—')</p></div>
                </div>
            </div>
        </header>
        <main class="p-6">
            @yield('content')
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>


