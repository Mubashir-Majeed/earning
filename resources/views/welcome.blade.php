<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>VideoEarn - Earn Money Watching AI Animated Videos</title>
        <meta name="description" content="Earn real money by watching AI animated videos. Make $100+ daily by completing video tasks. Join thousands of users earning from home.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <!-- Tailwind CSS -->
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
    </head>
    <body class="font-inter bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white overflow-x-hidden">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 bg-black/20 backdrop-blur-md border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                                <i class="fab fa-youtube text-red-500 mr-1 sm:mr-2"></i>VideoEarn
                            </h1>
                        </div>
                    </div>
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center">
                        <div class="ml-10 flex items-baseline space-x-6 lg:space-x-8">
                            <a href="#features" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Features</a>
                            <a href="#how-it-works" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors">How It Works</a>
                            <a href="#pricing" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Pricing</a>
                            <a href="#testimonials" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Reviews</a>
                        </div>
                        <div class="ml-6 flex items-center space-x-3 lg:space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-4 lg:px-6 py-2 rounded-full font-semibold text-sm lg:text-base hover:shadow-lg hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 lg:px-4 py-2 text-sm font-medium transition-colors">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-4 lg:px-6 py-2 rounded-full font-semibold text-sm lg:text-base hover:shadow-lg hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105">
                                    Get Started
                                </a>
                            @endauth
                        </div>
                    </div>
                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center space-x-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-3 py-1.5 rounded-full font-semibold text-xs hover:shadow-lg transition-all">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-2 py-1.5 text-xs font-medium transition-colors">
                                Login
                            </a>
                        @endauth
                        <button id="mobile-menu-button" class="text-white p-2 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/10 mt-2">
                    <div class="flex flex-col space-y-2 pt-4">
                        <a href="#features" class="text-gray-300 hover:text-white px-4 py-2 text-sm font-medium transition-colors">Features</a>
                        <a href="#how-it-works" class="text-gray-300 hover:text-white px-4 py-2 text-sm font-medium transition-colors">How It Works</a>
                        <a href="#pricing" class="text-gray-300 hover:text-white px-4 py-2 text-sm font-medium transition-colors">Pricing</a>
                        <a href="#testimonials" class="text-gray-300 hover:text-white px-4 py-2 text-sm font-medium transition-colors">Reviews</a>
                        @guest
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-4 py-2 rounded-full font-semibold text-sm text-center mt-2 hover:shadow-lg transition-all">
                                Get Started
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-24 sm:pt-28 pb-12 sm:pb-16 px-4 sm:px-6 lg:px-8 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <div class="space-y-6 sm:space-y-8 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                        <div class="space-y-3 sm:space-y-4">
                            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-tight">
                                <span class="bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 bg-clip-text text-transparent">
                                    Earn Money
                                </span>
                                <br>
                                <span class="text-white">Watching Videos</span>
                            </h1>
                            <p class="text-base sm:text-lg lg:text-xl text-gray-300 leading-relaxed max-w-lg mx-auto lg:mx-0">
                                Join thousands of users earning $100+ daily by watching AI animated videos. 
                                Simple, secure, and profitable!
                            </p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 text-center">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 text-center">
                                    <i class="fas fa-rocket mr-2"></i>
                                    Start Earning Now
                                </a>
                            @endauth
                            <a href="#how-it-works" class="border-2 border-white/20 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold text-base sm:text-lg hover:bg-white/10 transition-all duration-300 text-center">
                                <i class="fas fa-play mr-2"></i>
                                How It Works
                            </a>
                        </div>

                        <div class="grid grid-cols-3 gap-4 sm:gap-6 lg:gap-8 pt-6 sm:pt-8">
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-yellow-400">$100+</div>
                                <div class="text-xs sm:text-sm text-gray-400 mt-1">Daily Earnings</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-red-400">750</div>
                                <div class="text-xs sm:text-sm text-gray-400 mt-1">Points = $80</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-green-400">48h</div>
                                <div class="text-xs sm:text-sm text-gray-400 mt-1">Withdrawal Time</div>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-8 lg:mt-0" data-aos="fade-left" data-aos-duration="1000">
                        <div class="relative z-10">
                            <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-white/20 shadow-2xl">
                                <div class="space-y-4 sm:space-y-6">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-user text-black text-sm sm:text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-sm sm:text-base">John Doe</div>
                                                <div class="text-xs sm:text-sm text-gray-400">Active User</div>
                                            </div>
                                        </div>
                                        <div class="text-left sm:text-right w-full sm:w-auto">
                                            <div class="text-xl sm:text-2xl font-bold text-green-400">$247.50</div>
                                            <div class="text-xs sm:text-sm text-gray-400">Today's Earnings</div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 sm:space-y-4">
                                        <div class="flex items-center justify-between p-2 sm:p-3 bg-white/5 rounded-lg">
                                            <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                                <i class="fas fa-play-circle text-red-500 flex-shrink-0"></i>
                                                <span class="text-sm sm:text-base truncate">Hero's Journey</span>
                                            </div>
                                            <span class="text-green-400 font-semibold text-sm sm:text-base ml-2 flex-shrink-0">+15 pts</span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 sm:p-3 bg-white/5 rounded-lg">
                                            <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                                <i class="fas fa-play-circle text-red-500 flex-shrink-0"></i>
                                                <span class="text-sm sm:text-base truncate">Nation Builders</span>
                                            </div>
                                            <span class="text-green-400 font-semibold text-sm sm:text-base ml-2 flex-shrink-0">+20 pts</span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 sm:p-3 bg-white/5 rounded-lg">
                                            <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                                <i class="fas fa-play-circle text-red-500 flex-shrink-0"></i>
                                                <span class="text-sm sm:text-base truncate">Ancient Mysteries</span>
                                            </div>
                                            <span class="text-green-400 font-semibold text-sm sm:text-base ml-2 flex-shrink-0">+25 pts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating elements - hidden on mobile -->
                        <div class="hidden sm:block absolute -top-4 -right-4 w-16 h-16 sm:w-20 sm:h-20 bg-yellow-400/20 rounded-full animate-pulse"></div>
                        <div class="hidden sm:block absolute -bottom-4 -left-4 w-12 h-12 sm:w-16 sm:h-16 bg-red-500/20 rounded-full animate-bounce"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8 bg-black/20">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                        <span class="bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                            Why Choose VideoEarn?
                        </span>
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-300 max-w-3xl mx-auto px-4">
                        Join the most trusted platform for earning money by watching videos. 
                        We provide real earnings, secure payments, and 24/7 support.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 hover:border-yellow-400/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-yellow-400 to-red-500 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-dollar-sign text-black text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">High Earnings</h3>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Earn up to $100+ daily by watching AI animated videos. 
                            750 points equals $80 with instant payouts.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 hover:border-green-400/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shield-alt text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Secure & Safe</h3>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Bank-level security with encrypted transactions. 
                            Your data and earnings are completely protected.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 hover:border-purple-400/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clock text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Quick Withdrawals</h3>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Get your earnings in just 48 hours. 
                            Low withdrawal fees of only 5-7.5%.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 hover:border-blue-400/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-video text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">AI Animated Content</h3>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Watch engaging AI animated videos across multiple categories: 
                            Heroism, Nation Builders, Histories, Mysteries and more.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 hover:border-red-400/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="500">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-red-400 to-orange-500 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-mobile-alt text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Mobile Friendly</h3>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Access your earnings anywhere, anytime. 
                            Fully responsive design works perfectly on all devices.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 hover:border-indigo-400/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="600">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-headset text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">24/7 Support</h3>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed">
                            Our dedicated support team is available 24/7 
                            to help you with any questions or issues.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                        <span class="bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                            How It Works
                        </span>
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-300 max-w-3xl mx-auto px-4">
                        Start earning in just 4 simple steps. It's that easy!
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 relative">
                            <span class="text-black font-bold text-xl sm:text-2xl">1</span>
                            <div class="absolute -top-2 -right-2 w-5 h-5 sm:w-6 sm:h-6 bg-green-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-black text-xs"></i>
                            </div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Sign Up</h3>
                        <p class="text-sm sm:text-base text-gray-300 px-2">
                            Create your free account with just your email address. 
                            No complex verification needed.
                        </p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 relative">
                            <span class="text-black font-bold text-xl sm:text-2xl">2</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Make Deposit</h3>
                        <p class="text-sm sm:text-base text-gray-300 px-2">
                            Make a one-time $100 deposit to unlock video tasks. 
                            This is the only payment you'll ever need to make.
                        </p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 relative">
                            <span class="text-black font-bold text-xl sm:text-2xl">3</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Watch Videos</h3>
                        <p class="text-sm sm:text-base text-gray-300 px-2">
                            Get 5-10 daily video tasks. Watch complete videos 
                            to earn points that convert to real money.
                        </p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 relative">
                            <span class="text-black font-bold text-xl sm:text-2xl">4</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Get Paid</h3>
                        <p class="text-sm sm:text-base text-gray-300 px-2">
                            Withdraw your earnings in 48 hours. 
                            Low fees and multiple payment options available.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8 bg-black/20">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                        <span class="bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                            Simple Pricing
                        </span>
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-300 max-w-3xl mx-auto px-4">
                        One-time payment, lifetime access to earning opportunities.
                    </p>
                </div>

                <div class="max-w-md mx-auto" data-aos="zoom-in">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-white/20 relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-gradient-to-l from-yellow-400 to-red-500 text-black px-4 sm:px-6 py-1.5 sm:py-2 rounded-bl-xl sm:rounded-bl-2xl">
                            <span class="font-bold text-xs sm:text-sm">POPULAR</span>
                        </div>
                        
                        <div class="text-center mb-6 sm:mb-8 mt-4 sm:mt-0">
                            <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Starter Package</h3>
                            <div class="text-4xl sm:text-5xl font-bold mb-2">
                                <span class="bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">$100</span>
                            </div>
                            <p class="text-sm sm:text-base text-gray-300">One-time payment</p>
                        </div>

                        <div class="space-y-3 sm:space-y-4 mb-6 sm:mb-8">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-400 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base">Access to all video tasks</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-400 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base">5-10 videos daily</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-400 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base">750 points = $80 conversion</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-400 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base">48-hour withdrawals</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-400 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base">24/7 customer support</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-400 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base">Mobile-friendly platform</span>
                            </div>
                        </div>

                        @auth
                            <a href="{{ url('/dashboard') }}" class="w-full bg-gradient-to-r from-yellow-400 to-red-500 text-black py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 text-center block">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full bg-gradient-to-r from-yellow-400 to-red-500 text-black py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 text-center block">
                                Get Started Now
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                        <span class="bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                            What Our Users Say
                        </span>
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-300 max-w-3xl mx-auto px-4">
                        Join thousands of satisfied users earning money daily.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center mb-4 sm:mb-6">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-yellow-400 to-red-500 rounded-full flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                                <span class="text-black font-bold text-sm sm:text-base">SM</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm sm:text-base">Sarah Miller</h4>
                                <div class="flex text-yellow-400 text-xs sm:text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base text-gray-300 italic">
                            "I've been earning $150+ daily for the past 3 months. 
                            The platform is legit and payments are always on time!"
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center mb-4 sm:mb-6">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                                <span class="text-white font-bold text-sm sm:text-base">JD</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm sm:text-base">John Davis</h4>
                                <div class="flex text-yellow-400 text-xs sm:text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base text-gray-300 italic">
                            "Best investment I ever made! The videos are entertaining 
                            and I'm making more than my regular job."
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-white/20 sm:col-span-2 lg:col-span-1" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center mb-4 sm:mb-6">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                                <span class="text-white font-bold text-sm sm:text-base">EL</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm sm:text-base">Emily Lopez</h4>
                                <div class="flex text-yellow-400 text-xs sm:text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base text-gray-300 italic">
                            "Customer support is amazing and the withdrawal process 
                            is super fast. Highly recommended!"
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-yellow-400/10 to-red-500/10">
            <div class="max-w-4xl mx-auto text-center" data-aos="zoom-in">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                    Ready to Start Earning?
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-300 mb-6 sm:mb-8 px-4">
                    Join thousands of users who are already making money by watching videos. 
                    Start your journey today!
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-yellow-400 to-red-500 text-black px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-rocket mr-2"></i>
                            Start Earning Now
                        </a>
                    @endauth
                    <a href="#features" class="border-2 border-white/20 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold text-base sm:text-lg hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-info-circle mr-2"></i>
                        Learn More
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-black/40 py-8 sm:py-12 px-4 sm:px-6 lg:px-8 border-t border-white/10">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <div class="sm:col-span-2">
                        <div class="flex items-center mb-3 sm:mb-4">
                            <h3 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-yellow-400 to-red-500 bg-clip-text text-transparent">
                                <i class="fab fa-youtube text-red-500 mr-2"></i>VideoEarn
                            </h3>
                        </div>
                        <p class="text-sm sm:text-base text-gray-300 mb-3 sm:mb-4 max-w-md">
                            The most trusted platform for earning money by watching AI animated videos. 
                            Join thousands of satisfied users today.
                        </p>
                        <div class="flex space-x-3 sm:space-x-4">
                            <a href="#" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors text-sm sm:text-base">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors text-sm sm:text-base">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors text-sm sm:text-base">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors text-sm sm:text-base">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#features" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Features</a></li>
                            <li><a href="#how-it-works" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">How It Works</a></li>
                            <li><a href="#pricing" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Pricing</a></li>
                            <li><a href="#testimonials" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Reviews</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Support</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="#" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="#" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="text-sm sm:text-base text-gray-300 hover:text-white transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-white/10 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center">
                    <p class="text-xs sm:text-sm text-gray-400">
                        © 2024 VideoEarn. All rights reserved. | 
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a> | 
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    </p>
                </div>
            </div>
        </footer>

        <!-- AOS Animation Library -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });

            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                const icon = this.querySelector('i');
                menu.classList.toggle('hidden');
                if (menu.classList.contains('hidden')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            });

            // Close mobile menu when clicking on a link
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.add('hidden');
                    const icon = document.querySelector('#mobile-menu-button i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
        </script>
    </body>
</html>
