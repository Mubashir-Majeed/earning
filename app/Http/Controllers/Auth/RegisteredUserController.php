<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Send OTP to user's email
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $email = strtolower($request->email);
        
        // Check if OTP was sent recently (prevent spam)
        $lastOtpTime = Cache::get("otp_sent_{$email}");
        if ($lastOtpTime && now()->diffInSeconds($lastOtpTime) < 60) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before requesting a new OTP code.',
                'retry_after' => 60 - now()->diffInSeconds($lastOtpTime)
            ], 429);
        }

        // Generate 6-digit OTP
        $otp = str_pad((string) rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in cache for 3 minutes (180 seconds)
        Cache::put("otp_{$email}", $otp, now()->addMinutes(3));
        Cache::put("otp_sent_{$email}", now(), now()->addMinutes(1));
        
        // Store name if provided
        if ($request->name) {
            Cache::put("otp_name_{$email}", $request->name, now()->addMinutes(3));
        }

        try {
            // Send OTP email
            Mail::to($email)->send(new OtpVerificationMail($otp, $request->name));
            
            return response()->json([
                'success' => true,
                'message' => 'OTP has been sent to your email address.',
                'expires_in' => 180 // 3 minutes in seconds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = strtolower($request->email);
        $cachedOtp = Cache::get("otp_{$email}");

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ], 400);
        }

        if ($cachedOtp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP code. Please try again.'
            ], 400);
        }

        // Mark OTP as verified (valid for 10 minutes)
        Cache::put("otp_verified_{$email}", true, now()->addMinutes(10));
        
        // Remove the OTP from cache
        Cache::forget("otp_{$email}");

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Merge email_backup into email if email is empty
        if (empty($request->email) && !empty($request->email_backup)) {
            $request->merge(['email' => $request->email_backup]);
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referrer_id' => ['nullable', 'string', 'exists:users,referral_code'],
            'otp_verified' => ['required', 'accepted'],
        ]);

        $email = strtolower($request->email);
        
        // Verify OTP was verified
        if (!Cache::get("otp_verified_{$email}")) {
            throw ValidationException::withMessages([
                'email' => ['Email verification is required. Please verify your email with OTP.'],
            ]);
        }

        $referrer = null;
        $referralCode = $request->referrer_id ?: $request->ref;
        
        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'referrer_id' => $referrer?->id,
            'level' => 1, // Default to level 1
        ]);

        // Clear OTP verification cache
        Cache::forget("otp_verified_{$email}");
        Cache::forget("otp_name_{$email}");

        // No bonus awarded to new users - they must deposit Pro package for referrer to earn

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
