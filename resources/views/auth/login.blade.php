<x-guest-layout>
    @section('title', 'Welcome Back')
    @section('subtitle', 'Sign in to your account and start earning')

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-xl text-green-300 text-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-400"></i>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-300">
                <i class="fas fa-envelope mr-2 text-yellow-400"></i>Email Address
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                   placeholder="Enter your email address">
            @error('email')
                <p class="text-red-400 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-gray-300">
                <i class="fas fa-lock mr-2 text-yellow-400"></i>Password
            </label>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                       placeholder="Enter your password">
                <button type="button" 
                        onclick="togglePassword('password')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-eye" id="password-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="text-red-400 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember"
                       class="w-4 h-4 text-yellow-400 bg-white/10 border-white/20 rounded focus:ring-yellow-400 focus:ring-2">
                <span class="ml-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-yellow-400 hover:text-yellow-300 transition-colors" 
                   href="{{ route('password.request') }}">
                    <i class="fas fa-key mr-1"></i>{{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                id="login-button"
                class="w-full bg-gradient-to-r from-yellow-400 to-red-500 text-black py-3 px-6 rounded-xl font-bold text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-sign-in-alt mr-2"></i><span id="login-text">{{ __('Sign In') }}</span>
            <span id="login-spinner" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Signing In...</span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-slate-900 text-gray-400">New to VideoEarn?</span>
            </div>
        </div>

        <!-- Sign Up Link -->
        <div class="text-center">
            <a href="{{ route('register') }}" 
               class="inline-flex items-center text-yellow-400 hover:text-yellow-300 transition-colors font-medium">
                <i class="fas fa-user-plus mr-2"></i>Create your account and start earning
            </a>
        </div>
    </form>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loginButton = document.getElementById('login-button');
            const loginText = document.getElementById('login-text');
            const loginSpinner = document.getElementById('login-spinner');

            form.addEventListener('submit', function(e) {
                // Show loading state
                loginButton.disabled = true;
                loginText.classList.add('hidden');
                loginSpinner.classList.remove('hidden');
                
                // Allow form to submit normally
                // The loading state will persist until page reloads or redirects
            });
        });
    </script>
</x-guest-layout>
