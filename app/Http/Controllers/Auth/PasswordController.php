<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Send OTP to user's email for password change verification.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $user = $request->user();
        $email = strtolower($user->email);
        
        // Check if OTP was sent recently (prevent spam)
        $lastOtpTime = Cache::get("password_otp_sent_{$email}");
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
        Cache::put("password_otp_{$email}", $otp, now()->addMinutes(3));
        Cache::put("password_otp_sent_{$email}", now(), now()->addMinutes(1));

        try {
            // Send OTP email
            Mail::to($email)->send(new OtpVerificationMail($otp, $user->name));
            
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
     * Verify OTP for password change.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $user = $request->user();
        $email = strtolower($user->email);
        
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get("password_otp_{$email}");

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
        Cache::put("password_otp_verified_{$email}", true, now()->addMinutes(10));
        
        // Remove the OTP from cache
        Cache::forget("password_otp_{$email}");

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $email = strtolower($user->email);
        
        // Verify OTP was verified
        if (!Cache::get("password_otp_verified_{$email}")) {
            throw ValidationException::withMessages([
                'password' => ['Email verification is required. Please verify your email with OTP before changing password.'],
            ])->errorBag('updatePassword');
        }

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Clear OTP verification cache
        Cache::forget("password_otp_verified_{$email}");

        return back()->with('status', 'password-updated');
    }
}
