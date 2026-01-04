<x-guest-layout>
    @section('title', 'Join Earn Quest')
    @section('subtitle', 'Create your account and start earning money today')

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>

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

        <!-- Hidden fields for OTP verification -->
        <input type="hidden" name="otp_verified" id="otp_verified" value="0">
        <input type="hidden" name="email_backup" id="email_backup" value="">

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
                <i class="fas fa-gift mr-2 text-yellow-400"></i>Referral Code <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <input id="referrer_id" 
                       type="text" 
                       name="referrer_id" 
                       value="{{ request('ref') ?: old('referrer_id') }}" 
                       required
                       autocomplete="off"
                       maxlength="6"
                       minlength="6"
                       style="text-transform: uppercase;"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300"
                       placeholder="Enter referral code (e.g., A12P5)">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <i class="fas fa-user-plus text-gray-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1">
                <i class="fas fa-info-circle mr-1"></i>A valid referral code is required to create an account.
            </p>
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
    </form>

    <!-- OTP Verification Modal -->
    <div id="otp-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm" onclick="if(event.target === this) closeOtpModal()">
        <div class="relative w-full max-w-md mx-4" onclick="event.stopPropagation()">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-2xl border border-white/10 p-8 animate-fade-in">
                <!-- Modal Header -->
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-yellow-400/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-envelope-open-text text-yellow-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Verify Your Email</h3>
                    <p class="text-gray-400 text-sm">
                        We've sent a 6-digit verification code to<br>
                        <span id="modal-email" class="text-yellow-400 font-semibold"></span>
                    </p>
                </div>

                <!-- OTP Input -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-key mr-2 text-yellow-400"></i>Enter Verification Code
                        </label>
                        <input id="modal-otp" 
                               type="text" 
                               maxlength="6"
                               autocomplete="off"
                               class="w-full px-6 py-4 bg-white/10 border-2 border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 text-center text-2xl sm:text-3xl font-mono font-bold"
                               placeholder="000000"
                               style="letter-spacing: 0.25em; font-size: 1.75rem;">
                        <div id="modal-otp-error" class="hidden mt-2 text-sm text-red-400 text-center"></div>
                    </div>

                    <!-- Timer and Resend -->
                    <div class="flex items-center justify-between">
                        <div id="modal-timer" class="flex items-center text-sm text-gray-400">
                            <i class="fas fa-clock mr-2"></i>
                            <span id="modal-timer-text">03:00</span>
                        </div>
                        <button type="button" 
                                id="modal-resend-btn"
                                onclick="resendOtp()"
                                class="hidden text-sm text-yellow-400 hover:text-yellow-300 transition-colors font-medium">
                            <i class="fas fa-redo mr-1"></i>Resend Code
                        </button>
                    </div>

                    <!-- Success Message -->
                    <div id="modal-success" class="hidden p-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-300 text-sm text-center">
                        <i class="fas fa-check-circle mr-2"></i>Email verified successfully!
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-6 space-y-3">
                    <button type="button" 
                            id="modal-verify-btn"
                            onclick="verifyOtpInModal()"
                            class="w-full bg-gradient-to-r from-yellow-400 to-red-500 text-black py-3 px-6 rounded-xl font-bold hover:shadow-2xl hover:shadow-yellow-500/25 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="modal-verify-text">Verify & Continue</span>
                        <span id="modal-verify-spinner" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Verifying...</span>
                    </button>
                    <button type="button" 
                            onclick="closeOtpModal()"
                            class="w-full bg-white/10 text-gray-300 py-3 px-6 rounded-xl font-medium hover:bg-white/20 transition-all duration-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

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
        let userEmail = '';

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

        // Open OTP Modal
        function openOtpModal(email) {
            userEmail = email;
            const modal = document.getElementById('otp-modal');
            const modalEmail = document.getElementById('modal-email');
            const modalOtp = document.getElementById('modal-otp');
            
            modalEmail.textContent = email;
            modalOtp.value = '';
            modalOtp.classList.remove('border-red-500', 'border-green-500');
            document.getElementById('modal-otp-error').classList.add('hidden');
            document.getElementById('modal-success').classList.add('hidden');
            document.getElementById('modal-resend-btn').classList.add('hidden');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Auto-send OTP when modal opens
            sendOtpToModal();
            
            // Focus on OTP input
            setTimeout(() => modalOtp.focus(), 300);
        }

        // Close OTP Modal
        function closeOtpModal() {
            const modal = document.getElementById('otp-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            clearTimer();
        }

        // Send OTP to Modal
        async function sendOtpToModal() {
            const emailInput = document.getElementById('email');
            const nameInput = document.getElementById('name');
            const email = emailInput.value.trim();
            const name = nameInput.value.trim();

            if (!email || !isValidEmail(email)) {
                showModalError('Please enter a valid email address.');
                return;
            }

            const verifyBtn = document.getElementById('modal-verify-btn');
            const verifyText = document.getElementById('modal-verify-text');
            const verifySpinner = document.getElementById('modal-verify-spinner');

            verifyBtn.disabled = true;
            verifyText.classList.add('hidden');
            verifySpinner.classList.remove('hidden');

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
                    timeLeft = data.expires_in || 180;
                    startModalTimer();
                    verifyBtn.disabled = false;
                    verifyText.classList.remove('hidden');
                    verifySpinner.classList.add('hidden');
                } else {
                    showModalError(data.message || 'Failed to send OTP. Please try again.');
                    verifyBtn.disabled = false;
                    verifyText.classList.remove('hidden');
                    verifySpinner.classList.add('hidden');
                }
            } catch (error) {
                showModalError('An error occurred. Please try again.');
                verifyBtn.disabled = false;
                verifyText.classList.remove('hidden');
                verifySpinner.classList.add('hidden');
            }
        }

        // Resend OTP
        function resendOtp() {
            document.getElementById('modal-resend-btn').classList.add('hidden');
            timeLeft = 180;
            startModalTimer();
            sendOtpToModal();
        }

        // Verify OTP in Modal
        async function verifyOtpInModal() {
            const otpInput = document.getElementById('modal-otp');
            const emailInput = document.getElementById('email');
            const otp = otpInput.value.trim();
            const email = emailInput.value.trim();

            if (otp.length !== 6) {
                showModalError('Please enter a 6-digit verification code.');
                return;
            }

            const verifyBtn = document.getElementById('modal-verify-btn');
            const verifyText = document.getElementById('modal-verify-text');
            const verifySpinner = document.getElementById('modal-verify-spinner');

            verifyBtn.disabled = true;
            verifyText.classList.add('hidden');
            verifySpinner.classList.remove('hidden');

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
                    document.getElementById('email_backup').value = email;
                    
                    otpInput.classList.add('border-green-500');
                    otpInput.classList.remove('border-red-500');
                    otpInput.disabled = true;
                    
                    document.getElementById('modal-otp-error').classList.add('hidden');
                    document.getElementById('modal-success').classList.remove('hidden');
                    
                    clearTimer();
                    
                    // Close modal and submit form after a short delay
                    setTimeout(() => {
                        closeOtpModal();
                        document.querySelector('form').submit();
                    }, 1000);
                } else {
                    showModalError(data.message || 'Invalid verification code. Please try again.');
                    otpInput.classList.add('border-red-500');
                    otpInput.classList.remove('border-green-500');
                    setTimeout(() => {
                        otpInput.classList.remove('border-red-500');
                    }, 2000);
                    verifyBtn.disabled = false;
                    verifyText.classList.remove('hidden');
                    verifySpinner.classList.add('hidden');
                }
            } catch (error) {
                showModalError('An error occurred. Please try again.');
                verifyBtn.disabled = false;
                verifyText.classList.remove('hidden');
                verifySpinner.classList.add('hidden');
            }
        }

        // Modal Timer
        function startModalTimer() {
            clearTimer();
            updateModalTimerDisplay();
            otpTimer = setInterval(() => {
                timeLeft--;
                updateModalTimerDisplay();
                if (timeLeft <= 0) {
                    clearTimer();
                    document.getElementById('modal-resend-btn').classList.remove('hidden');
                }
            }, 1000);
        }

        function updateModalTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timerText = document.getElementById('modal-timer-text');
            timerText.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (timeLeft <= 30) {
                timerText.classList.add('text-red-400');
                timerText.classList.remove('text-gray-400');
            } else {
                timerText.classList.remove('text-red-400');
                timerText.classList.add('text-gray-400');
            }
        }

        function showModalError(message) {
            const errorDiv = document.getElementById('modal-otp-error');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function clearTimer() {
            if (otpTimer) {
                clearInterval(otpTimer);
                otpTimer = null;
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
            const emailInput = document.getElementById('email');

            // Auto-uppercase referral code input and validate
            if (referralInput) {
                referralInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    
                    // Validate referral code length
                    if (this.value.length === 6) {
                        // Optionally validate referral code exists via AJAX
                        validateReferralCode(this.value);
                    }
                });
                
                referralInput.addEventListener('blur', function() {
                    if (this.value.length === 6) {
                        validateReferralCode(this.value);
                    }
                });
            }
            
            // Validate referral code function
            async function validateReferralCode(code) {
                if (!code || code.length !== 6) return;
                
                try {
                    const response = await fetch('{{ route("register") }}', {
                        method: 'HEAD',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                } catch (error) {
                    // Silent fail - validation will happen on server side
                }
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
                e.preventDefault();
                
                // Validate all fields first
                const email = emailInput.value.trim();
                const name = document.getElementById('name').value.trim();
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                const referralCode = referralInput.value.trim().toUpperCase();
                const terms = document.getElementById('terms').checked;

                // Basic validation
                if (!email || !isValidEmail(email)) {
                    alert('Please enter a valid email address.');
                    emailInput.focus();
                    return false;
                }

                if (!name) {
                    alert('Please enter your full name.');
                    document.getElementById('name').focus();
                    return false;
                }

                if (!password || password.length < 8) {
                    alert('Password must be at least 8 characters long.');
                    document.getElementById('password').focus();
                    return false;
                }

                if (password !== passwordConfirmation) {
                    alert('Passwords do not match.');
                    document.getElementById('password_confirmation').focus();
                    return false;
                }

                if (!referralCode || referralCode.length !== 6) {
                    alert('Please enter a valid 6-character referral code.');
                    referralInput.focus();
                    return false;
                }

                if (!terms) {
                    alert('Please agree to the Terms of Service and Privacy Policy.');
                    return false;
                }

                // Check if OTP is already verified
                if (isOtpVerified && document.getElementById('otp_verified').value === '1') {
                    // OTP already verified, submit form
                    document.getElementById('email_backup').value = email;
                    registerButton.disabled = true;
                    registerText.classList.add('hidden');
                    registerSpinner.classList.remove('hidden');
                    this.submit();
                    return true;
                }

                // Show OTP modal
                openOtpModal(email);
                return false;
            });

            // Auto-verify OTP when 6 digits are entered
            const modalOtpInput = document.getElementById('modal-otp');
            if (modalOtpInput) {
                modalOtpInput.addEventListener('input', function() {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value.length === 6 && !isOtpVerified) {
                        verifyOtpInModal();
                    }
                });

                modalOtpInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && this.value.length === 6) {
                        e.preventDefault();
                        verifyOtpInModal();
                    }
                });
            }
        });
    </script>
</x-guest-layout>

