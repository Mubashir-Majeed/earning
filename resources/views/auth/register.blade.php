<x-guest-layout>
    @section('title', 'Join Earn Quest')
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
            <div class="relative">
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="username"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300 pr-32"
                       placeholder="Enter your email address">
                <button type="button" 
                        id="send-otp-btn"
                        onclick="sendOtp()"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-semibold rounded-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="send-otp-text">Send OTP</span>
                    <span id="send-otp-spinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
            @error('email')
                <p class="text-red-400 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
            <div id="otp-message" class="hidden mt-2"></div>
        </div>

        <!-- OTP Verification -->
        <div id="otp-section" class="space-y-2 hidden">
            <label for="otp" class="block text-sm font-medium text-gray-300">
                <i class="fas fa-key mr-2 text-yellow-400"></i>Enter OTP Code
            </label>
            <div class="relative">
                <input id="otp" 
                       type="text" 
                       name="otp" 
                       maxlength="6"
                       autocomplete="off"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300 text-center text-2xl font-mono tracking-widest"
                       placeholder="000000">
            </div>
            <div class="flex items-center justify-between mt-2">
                <div id="otp-timer" class="text-sm text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="timer-text">03:00</span>
                </div>
                <button type="button" 
                        id="resend-otp-btn"
                        onclick="sendOtp(true)"
                        class="hidden text-sm text-yellow-400 hover:text-yellow-300 transition-colors font-medium">
                    <i class="fas fa-redo mr-1"></i>Resend OTP
                </button>
            </div>
            <div id="otp-verify-message" class="hidden mt-2"></div>
            <input type="hidden" name="otp_verified" id="otp_verified" value="0">
            <!-- Hidden email backup to ensure email is always submitted -->
            <input type="hidden" name="email_backup" id="email_backup" value="">
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
                <a href="{{ route('terms') }}" target="_blank" class="text-yellow-400 hover:text-yellow-300 transition-colors">Terms of Service</a> 
                and 
                <a href="{{ route('privacy') }}" target="_blank" class="text-yellow-400 hover:text-yellow-300 transition-colors">Privacy Policy</a>
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
        let otpTimer = null;
        let timeLeft = 180; // 3 minutes in seconds
        let isOtpVerified = false;

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

        // Send OTP
        async function sendOtp(isResend = false) {
            const emailInput = document.getElementById('email');
            const nameInput = document.getElementById('name');
            const email = emailInput.value.trim();
            const name = nameInput.value.trim();
            const resendBtn = document.getElementById('resend-otp-btn');

            if (!email) {
                showMessage('otp-message', 'Please enter your email address first.', 'error');
                emailInput.focus();
                return;
            }

            if (!isValidEmail(email)) {
                showMessage('otp-message', 'Please enter a valid email address.', 'error');
                return;
            }

            const sendBtn = document.getElementById('send-otp-btn');
            const sendText = document.getElementById('send-otp-text');
            const sendSpinner = document.getElementById('send-otp-spinner');

            // Disable button
            sendBtn.disabled = true;
            sendText.classList.add('hidden');
            sendSpinner.classList.remove('hidden');

            try {
                const response = await fetch('{{ route("register.send-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email, name })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('otp-message', data.message, 'success');
                    document.getElementById('otp-section').classList.remove('hidden');
                    document.getElementById('otp').focus();
                    
                    // Make email input readonly (not disabled) so it still submits with form
                    emailInput.readOnly = true;
                    emailInput.classList.add('opacity-75', 'cursor-not-allowed');
                    
                    // Also set hidden backup field
                    const emailBackup = document.getElementById('email_backup');
                    if (emailBackup) {
                        emailBackup.value = email;
                    }
                    
                    // Start timer
                    timeLeft = data.expires_in || 180;
                    startTimer();
                    
                    // Hide resend button initially
                    resendBtn.classList.add('hidden');
                    
                    // Re-enable send button after a delay (for resend functionality)
                    if (isResend) {
                        setTimeout(() => {
                            sendBtn.disabled = false;
                            sendText.classList.remove('hidden');
                            sendSpinner.classList.add('hidden');
                        }, 1000);
                    }
                } else {
                    showMessage('otp-message', data.message, 'error');
                    if (data.retry_after) {
                        setTimeout(() => {
                            sendBtn.disabled = false;
                            sendText.classList.remove('hidden');
                            sendSpinner.classList.add('hidden');
                        }, data.retry_after * 1000);
                    } else {
                        sendBtn.disabled = false;
                        sendText.classList.remove('hidden');
                        sendSpinner.classList.add('hidden');
                    }
                }
            } catch (error) {
                showMessage('otp-message', 'An error occurred. Please try again.', 'error');
                sendBtn.disabled = false;
                sendText.classList.remove('hidden');
                sendSpinner.classList.add('hidden');
            }
        }

        // Verify OTP
        async function verifyOtp() {
            const otpInput = document.getElementById('otp');
            const emailInput = document.getElementById('email');
            const otp = otpInput.value.trim();
            const email = emailInput.value.trim();

            if (otp.length !== 6) {
                showMessage('otp-verify-message', 'Please enter a 6-digit OTP code.', 'error');
                return;
            }

            try {
                const response = await fetch('{{ route("register.verify-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email, otp })
                });

                const data = await response.json();

                if (data.success) {
                    isOtpVerified = true;
                    document.getElementById('otp_verified').value = '1';
                    showMessage('otp-verify-message', data.message, 'success');
                    otpInput.classList.add('border-green-500');
                    otpInput.disabled = true;
                    clearTimer();
                } else {
                    showMessage('otp-verify-message', data.message, 'error');
                    otpInput.classList.add('border-red-500');
                    setTimeout(() => {
                        otpInput.classList.remove('border-red-500');
                    }, 2000);
                }
            } catch (error) {
                showMessage('otp-verify-message', 'An error occurred. Please try again.', 'error');
            }
        }

        // Timer functions
        function startTimer() {
            clearTimer();
            updateTimerDisplay();
            otpTimer = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                if (timeLeft <= 0) {
                    clearTimer();
                    const resendBtn = document.getElementById('resend-otp-btn');
                    if (resendBtn) {
                        resendBtn.classList.remove('hidden');
                    }
                }
            }, 1000);
        }

        function clearTimer() {
            if (otpTimer) {
                clearInterval(otpTimer);
                otpTimer = null;
            }
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timerText = document.getElementById('timer-text');
            timerText.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (timeLeft <= 30) {
                timerText.classList.add('text-red-400');
            } else {
                timerText.classList.remove('text-red-400');
            }
        }

        // Helper functions
        function showMessage(elementId, message, type) {
            const element = document.getElementById(elementId);
            element.classList.remove('hidden');
            element.className = element.className.replace(/text-(red|green|yellow)-400/g, '');
            
            if (type === 'success') {
                element.className += ' text-green-400';
                element.innerHTML = `<i class="fas fa-check-circle mr-1"></i>${message}`;
            } else if (type === 'error') {
                element.className += ' text-red-400';
                element.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
            } else {
                element.className += ' text-yellow-400';
                element.innerHTML = `<i class="fas fa-info-circle mr-1"></i>${message}`;
            }
        }

        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const registerButton = document.getElementById('register-button');
            const registerText = document.getElementById('register-text');
            const registerSpinner = document.getElementById('register-spinner');
            const referralInput = document.getElementById('referrer_id');
            const otpInput = document.getElementById('otp');
            const emailInput = document.getElementById('email');

            // Auto-uppercase referral code input
            if (referralInput) {
                referralInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            // OTP input - auto verify on 6 digits
            if (otpInput) {
                otpInput.addEventListener('input', function() {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value.length === 6 && !isOtpVerified) {
                        verifyOtp();
                    }
                });

                // Allow Enter key to verify
                otpInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && this.value.length === 6) {
                        e.preventDefault();
                        verifyOtp();
                    }
                });
            }

            // Email input - update backup field whenever email changes
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    const emailBackup = document.getElementById('email_backup');
                    if (emailBackup) {
                        emailBackup.value = this.value.trim();
                    }
                });
                
                emailInput.addEventListener('blur', async function() {
                    const email = this.value.trim();
                    const emailBackup = document.getElementById('email_backup');
                    if (emailBackup && email) {
                        emailBackup.value = email;
                    }
                });
            }

            form.addEventListener('submit', function(e) {
                // Check if OTP is verified
                if (!isOtpVerified) {
                    e.preventDefault();
                    showMessage('otp-verify-message', 'Please verify your email with OTP before registering.', 'error');
                    if (document.getElementById('otp-section').classList.contains('hidden')) {
                        showMessage('otp-message', 'Please send OTP to your email first.', 'error');
                    }
                    return false;
                }

                // Ensure email is set in both fields before submit
                const emailValue = emailInput.value.trim();
                const emailBackup = document.getElementById('email_backup');
                if (emailBackup && emailValue) {
                    emailBackup.value = emailValue;
                }
                
                // If email input is readonly/disabled, ensure backup has the value
                if (!emailValue && emailBackup) {
                    emailInput.value = emailBackup.value;
                }

                // Show loading state
                registerButton.disabled = true;
                registerText.classList.add('hidden');
                registerSpinner.classList.remove('hidden');
                
                // Allow form to submit normally
            });
        });
    </script>
</x-guest-layout>
