<x-guest-layout>
    @section('title', 'Join VideoEarn')
    @section('subtitle', 'Create your account and start earning money today')

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-300">
                <i class="fas fa-user mr-2 text-yellow-400"></i>Full Name
            </label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                   placeholder="Enter your full name">
            @error('name')
                <p class="text-red-400 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

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
                       autocomplete="new-password"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                       placeholder="Create a strong password">
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

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">
                <i class="fas fa-lock mr-2 text-yellow-400"></i>Confirm Password
            </label>
            <div class="relative">
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                       placeholder="Confirm your password">
                <button type="button" 
                        onclick="togglePassword('password_confirmation')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="text-red-400 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Referral Code -->
        <div class="space-y-2">
            <label for="referrer_id" class="block text-sm font-medium text-gray-300">
                <i class="fas fa-gift mr-2 text-yellow-400"></i>Referral Code <span class="text-gray-400 text-xs">(Optional)</span>
            </label>
            <div class="relative">
                <input id="referrer_id" 
                       type="text" 
                       name="referrer_id" 
                       value="{{ request('ref') ?: old('referrer_id') }}" 
                       autocomplete="off"
                       maxlength="6"
                       style="text-transform: uppercase;"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                       placeholder="Enter 6-character code (e.g., ABC123)">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <i class="fas fa-user-plus text-gray-400"></i>
                </div>
            </div>
            @if(request('ref'))
                <p class="text-green-400 text-sm mt-1">
                    <i class="fas fa-check-circle mr-1"></i>Referral code automatically applied!
                </p>
            @endif
            @error('referrer_id')
                <p class="text-red-400 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
            <input id="terms" 
                   type="checkbox" 
                   required
                   class="w-4 h-4 text-yellow-400 bg-white/10 border-white/20 rounded focus:ring-yellow-400 focus:ring-2 mt-1">
            <label for="terms" class="ml-3 text-sm text-gray-300">
                I agree to the 
                <a href="#" class="text-yellow-400 hover:text-yellow-300 transition-colors">Terms of Service</a> 
                and 
                <a href="#" class="text-yellow-400 hover:text-yellow-300 transition-colors">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                id="register-button"
                class="w-full bg-gradient-to-r from-yellow-400 to-red-500 text-black py-3 px-6 rounded-xl font-bold text-lg hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-rocket mr-2"></i><span id="register-text">{{ __('Create Account & Start Earning') }}</span>
            <span id="register-spinner" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...</span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-slate-900 text-gray-400">Already have an account?</span>
            </div>
        </div>

        <!-- Sign In Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center text-yellow-400 hover:text-yellow-300 transition-colors font-medium">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign in to your account
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

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = getPasswordStrength(password);
            updatePasswordStrength(strength);
        });

        function getPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }

        function updatePasswordStrength(strength) {
            // You can add a visual strength indicator here if needed
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const registerButton = document.getElementById('register-button');
            const registerText = document.getElementById('register-text');
            const registerSpinner = document.getElementById('register-spinner');
            const referralInput = document.getElementById('referrer_id');

            // Auto-uppercase referral code input
            if (referralInput) {
                referralInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            form.addEventListener('submit', function(e) {
                // Show loading state
                registerButton.disabled = true;
                registerText.classList.add('hidden');
                registerSpinner.classList.remove('hidden');
                
                // Allow form to submit normally
                // The loading state will persist until page reloads or redirects
            });
        });
    </script>
</x-guest-layout>
