<form method="post" action="{{ route('password.update') }}" id="password-update-form" class="space-y-6">
    @csrf
    @method('put')

    <!-- Email Verification Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between mb-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Verification Required
                </label>
                <p class="text-xs text-gray-600">Verify your email with OTP before changing password</p>
            </div>
            <button type="button" 
                    id="send-otp-btn"
                    onclick="sendPasswordOtp()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                <span id="send-otp-text">Send OTP</span>
                <span id="send-otp-spinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
        </div>
        
        <div class="mb-3">
            <input type="email" 
                   value="{{ auth()->user()->email }}" 
                   readonly
                   class="w-full px-4 py-2 bg-white border border-blue-200 rounded-lg text-gray-700 text-sm font-mono opacity-75 cursor-not-allowed">
        </div>
        
        <div id="otp-message" class="hidden mb-3"></div>
        
        <!-- OTP Verification Section -->
        <div id="otp-section" class="hidden">
            <label for="password_otp" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-key mr-2 text-blue-600"></i>Enter OTP Code
            </label>
            <div class="relative">
                <input id="password_otp" 
                       type="text" 
                       name="otp" 
                       maxlength="6"
                       autocomplete="off"
                       class="w-full px-4 py-3 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-center text-2xl font-mono tracking-widest"
                       placeholder="000000">
            </div>
            <div class="flex items-center justify-between mt-2">
                <div id="otp-timer" class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="timer-text">03:00</span>
                </div>
                <button type="button" 
                        id="resend-otp-btn"
                        onclick="sendPasswordOtp(true)"
                        class="hidden text-sm text-blue-600 hover:text-blue-700 transition-colors font-medium">
                    <i class="fas fa-redo mr-1"></i>Resend OTP
                </button>
            </div>
            <div id="otp-verify-message" class="hidden mt-2"></div>
            <input type="hidden" name="otp_verified" id="otp_verified" value="0">
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-key mr-2 text-emerald-600"></i>{{ __('Current Password') }}
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="w-full px-4 py-3 border {{ $errors->updatePassword->has('current_password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" 
                autocomplete="current-password"
                placeholder="Enter your current password"
            />
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-emerald-600"></i>{{ __('New Password') }}
            </label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" 
                autocomplete="new-password"
                placeholder="Enter your new password"
            />
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-500 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i>Use a strong password with at least 8 characters
            </p>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-emerald-600"></i>{{ __('Confirm New Password') }}
            </label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" 
                autocomplete="new-password"
                placeholder="Confirm your new password"
            />
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <div>
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium flex items-center"
                >
                    <i class="fas fa-check-circle mr-2"></i>{{ __('Password updated successfully!') }}
                </p>
            @endif
        </div>
        <button type="submit" 
                id="update-password-btn"
                class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-lg hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-save"></i>
            <span id="update-password-text">{{ __('Update Password') }}</span>
            <span id="update-password-spinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
        </button>
    </div>
</form>

<script>
    let passwordOtpTimer = null;
    let passwordTimeLeft = 180; // 3 minutes in seconds
    let isPasswordOtpVerified = false;

    // Send OTP for password change
    async function sendPasswordOtp(isResend = false) {
        const sendBtn = document.getElementById('send-otp-btn');
        const sendText = document.getElementById('send-otp-text');
        const sendSpinner = document.getElementById('send-otp-spinner');
        const resendBtn = document.getElementById('resend-otp-btn');

        // Disable button
        sendBtn.disabled = true;
        sendText.classList.add('hidden');
        sendSpinner.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("password.send-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                showPasswordMessage('otp-message', data.message, 'success');
                document.getElementById('otp-section').classList.remove('hidden');
                document.getElementById('password_otp').focus();
                
                // Start timer
                passwordTimeLeft = data.expires_in || 180;
                startPasswordTimer();
                
                // Hide resend button initially
                resendBtn.classList.add('hidden');
                
                // Re-enable send button after a delay (for resend functionality)
                if (isResend) {
                    setTimeout(() => {
                        sendBtn.disabled = false;
                        sendText.classList.remove('hidden');
                        sendSpinner.classList.add('hidden');
                    }, 1000);
                } else {
                    setTimeout(() => {
                        sendBtn.disabled = false;
                        sendText.classList.remove('hidden');
                        sendSpinner.classList.add('hidden');
                    }, 1000);
                }
            } else {
                showPasswordMessage('otp-message', data.message, 'error');
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
            showPasswordMessage('otp-message', 'An error occurred. Please try again.', 'error');
            sendBtn.disabled = false;
            sendText.classList.remove('hidden');
            sendSpinner.classList.add('hidden');
        }
    }

    // Verify OTP for password change
    async function verifyPasswordOtp() {
        const otpInput = document.getElementById('password_otp');
        const otp = otpInput.value.trim();

        if (otp.length !== 6) {
            showPasswordMessage('otp-verify-message', 'Please enter a 6-digit OTP code.', 'error');
            return;
        }

        try {
            const response = await fetch('{{ route("password.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ otp })
            });

            const data = await response.json();

            if (data.success) {
                isPasswordOtpVerified = true;
                document.getElementById('otp_verified').value = '1';
                showPasswordMessage('otp-verify-message', data.message, 'success');
                otpInput.classList.add('border-green-500');
                otpInput.disabled = true;
                clearPasswordTimer();
            } else {
                showPasswordMessage('otp-verify-message', data.message, 'error');
                otpInput.classList.add('border-red-500');
                setTimeout(() => {
                    otpInput.classList.remove('border-red-500');
                }, 2000);
            }
        } catch (error) {
            showPasswordMessage('otp-verify-message', 'An error occurred. Please try again.', 'error');
        }
    }

    // Timer functions
    function startPasswordTimer() {
        clearPasswordTimer();
        updatePasswordTimerDisplay();
        passwordOtpTimer = setInterval(() => {
            passwordTimeLeft--;
            updatePasswordTimerDisplay();
            if (passwordTimeLeft <= 0) {
                clearPasswordTimer();
                document.getElementById('resend-otp-btn').classList.remove('hidden');
            }
        }, 1000);
    }

    function clearPasswordTimer() {
        if (passwordOtpTimer) {
            clearInterval(passwordOtpTimer);
            passwordOtpTimer = null;
        }
    }

    function updatePasswordTimerDisplay() {
        const minutes = Math.floor(passwordTimeLeft / 60);
        const seconds = passwordTimeLeft % 60;
        const timerText = document.getElementById('timer-text');
        timerText.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        if (passwordTimeLeft <= 30) {
            timerText.classList.add('text-red-600');
        } else {
            timerText.classList.remove('text-red-600');
        }
    }

    // Helper functions
    function showPasswordMessage(elementId, message, type) {
        const element = document.getElementById(elementId);
        element.classList.remove('hidden');
        element.className = element.className.replace(/text-(red|green|blue)-600/g, '');
        
        if (type === 'success') {
            element.className += ' text-green-600';
            element.innerHTML = `<i class="fas fa-check-circle mr-1"></i>${message}`;
        } else if (type === 'error') {
            element.className += ' text-red-600';
            element.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
        } else {
            element.className += ' text-blue-600';
            element.innerHTML = `<i class="fas fa-info-circle mr-1"></i>${message}`;
        }
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('password-update-form');
        const otpInput = document.getElementById('password_otp');
        const updateBtn = document.getElementById('update-password-btn');
        const updateText = document.getElementById('update-password-text');
        const updateSpinner = document.getElementById('update-password-spinner');

        // OTP input - auto verify on 6 digits
        if (otpInput) {
            otpInput.addEventListener('input', function() {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value.length === 6 && !isPasswordOtpVerified) {
                    verifyPasswordOtp();
                }
            });

            // Allow Enter key to verify
            otpInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.length === 6) {
                    e.preventDefault();
                    verifyPasswordOtp();
                }
            });
        }

        form.addEventListener('submit', function(e) {
            // Check if OTP is verified
            if (!isPasswordOtpVerified) {
                e.preventDefault();
                showPasswordMessage('otp-verify-message', 'Please verify your email with OTP before changing password.', 'error');
                if (document.getElementById('otp-section').classList.contains('hidden')) {
                    showPasswordMessage('otp-message', 'Please send OTP to your email first.', 'error');
                }
                return false;
            }

            // Show loading state
            updateBtn.disabled = true;
            updateText.classList.add('hidden');
            updateSpinner.classList.remove('hidden');
            
            // Allow form to submit normally
        });
    });
</script>
