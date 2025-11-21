<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Earn Quest - Earn Money Watching Videos')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        
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
                        animation: {
                            'float': 'float 6s ease-in-out infinite',
                            'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        },
                        keyframes: {
                            float: {
                                '0%, 100%': { transform: 'translateY(0px)' },
                                '50%': { transform: 'translateY(-20px)' },
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Custom Styles -->
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }
            
            .form-input:focus {
                transform: scale(1.02);
            }
            
            .floating-animation {
                animation: float 6s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-inter bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white antialiased min-h-screen">
        <!-- Background Animation -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-400/15 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-red-500/15 rounded-full blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-500"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full blur-2xl animate-pulse delay-700"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-green-500/10 rounded-full blur-2xl animate-pulse delay-300"></div>
        </div>

        <!-- Navigation -->
        <nav class="relative z-10 bg-black/20 backdrop-blur-md border-b border-white/10">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                            <i class="fab fa-youtube text-red-500 mr-1 sm:mr-2"></i><span class="hidden sm:inline">Earn Quest</span><span class="sm:hidden">EQ</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium transition-colors">
                            <i class="fas fa-home sm:mr-2"></i><span class="hidden sm:inline">Home</span>
                        </a>
                        @if (Route::currentRouteName() === 'login')
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-3 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-base font-semibold hover:shadow-lg hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-user-plus sm:mr-2"></i><span class="hidden sm:inline">Sign Up</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-3 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-base font-semibold hover:shadow-lg hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-sign-in-alt sm:mr-2"></i><span class="hidden sm:inline">Login</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="relative z-10 flex flex-col justify-center items-center min-h-screen pt-16 sm:pt-20 pb-6 sm:pb-8 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-white/20 shadow-2xl" data-aos="fade-up" data-aos-duration="1000">
                    <!-- Header -->
                    <div class="text-center mb-6 sm:mb-8">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-110 floating-animation">
                            @if (Route::currentRouteName() === 'login')
                                <i class="fas fa-sign-in-alt text-black text-xl sm:text-2xl"></i>
                            @else
                                <i class="fas fa-user-plus text-black text-xl sm:text-2xl"></i>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold mb-2 bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                            @yield('title', 'Welcome Back')
                        </h1>
                        <p class="text-sm sm:text-base text-gray-300">
                            @yield('subtitle', 'Access your account to start earning')
                        </p>
                    </div>

                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 sm:mt-8">
                    <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                        By continuing, you agree to our 
                        <a href="{{ route('terms') }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">Terms of Service</a> 
                        and 
                        <a href="{{ route('privacy') }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">Privacy Policy</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- AOS Animation Library -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        </script>
    </body>
</html>
