<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationMail;
use App\Models\Withdrawal;
use App\Traits\CreatesNotifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class WithdrawalController extends Controller
{
    use CreatesNotifications;
    
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * Send OTP to user's email for withdrawal verification.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $user = Auth::user();
        $email = strtolower($user->email);
        
        // Check if OTP was sent recently (prevent spam)
        $lastOtpTime = Cache::get("withdrawal_otp_sent_{$email}");
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
        Cache::put("withdrawal_otp_{$email}", $otp, now()->addMinutes(3));
        Cache::put("withdrawal_otp_sent_{$email}", now(), now()->addMinutes(1));

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
     * Verify OTP for withdrawal.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $user = Auth::user();
        $email = strtolower($user->email);
        
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get("withdrawal_otp_{$email}");

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
        Cache::put("withdrawal_otp_verified_{$email}", true, now()->addMinutes(10));
        
        // Remove the OTP from cache
        Cache::forget("withdrawal_otp_{$email}");

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    public function request(Request $request)
    {
        $user = Auth::user();
        $email = strtolower($user->email);

        $minWithdrawal = config('platform.min_withdrawal', 10);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $minWithdrawal],
            'otp_verified' => ['required', 'accepted'],
        ]);

        // Verify OTP was verified
        if (!Cache::get("withdrawal_otp_verified_{$email}")) {
            throw ValidationException::withMessages([
                'amount' => ['Email verification is required. Please verify your email with OTP before requesting withdrawal.'],
            ]);
        }

        if (!$user->has_deposited || !$user->investment_package) {
            return redirect()->route('withdrawal')->with('error', 'You must complete a package deposit before withdrawing.');
        }

        if (!$user->hasBoundWallet()) {
            return redirect()->route('withdrawal')->with('error', 'Bind your BEP20 wallet address before requesting a withdrawal.');
        }

        $package = config('investment.packages.' . $user->investment_package);
        if (!$package) {
            return redirect()->route('withdrawal')->with('error', 'Unable to determine your package details. Please contact support.');
        }

        $minWithdrawal = config('platform.min_withdrawal', 10);
        if ($request->amount < $minWithdrawal) {
            return redirect()->route('withdrawal')->with('error', 'Minimum withdrawal amount is $' . number_format($minWithdrawal, 2));
        }

        $availableProfit = $user->withdrawableProfit();
        $maxWithdrawalForPackage = min($availableProfit, $package['withdrawal_cap']);

        if ($request->amount > $maxWithdrawalForPackage) {
            return redirect()->route('withdrawal')->with('error', 'Maximum withdrawal for your package is $' . number_format($maxWithdrawalForPackage, 2));
        }

        // Enforce unwithdrawable minimum balance
        $unwithdrawableMin = $user->unwithdrawable_balance_min ?? 50;
        if (($user->balance - $request->amount) < $unwithdrawableMin) {
            return redirect()->route('withdrawal')->with('error', 'You must keep at least $'.number_format($unwithdrawableMin,2).' as un-withdrawable balance.');
        }
        if ($user->balance < $request->amount) {
            return redirect()->route('withdrawal')->with('error', 'Insufficient balance for withdrawal.');
        }

        // Enforce referral requirement per level
        if (!$user->meetsReferralRequirementForWithdrawal()) {
            return redirect()->route('withdrawal')->with('error', 'Referral requirement not met for your level.');
        }

        // Enforce monthly withdrawal quota
        if (!$user->withinMonthlyWithdrawalQuota()) {
            return redirect()->route('withdrawal')->with('error', 'Monthly withdrawal limit reached for your level.');
        }

        // Calculate withdrawal fee (5% default or per-level)
        $feePercent = config('platform.withdrawal_fee_percent', $user->withdrawalFeePercent() * 100) / 100;
        $feeAmount = round($request->amount * $feePercent, 2);
        $netAmount = $request->amount - $feeAmount;

        DB::transaction(function () use ($user, $request, $feeAmount, $netAmount) {
            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'fee_amount' => $feeAmount,
                'net_amount' => $netAmount,
                'currency' => 'USD',
                'withdrawal_method' => 'bep20',
                'withdrawal_details' => $user->bep20_address,
                'status' => 'pending',
                'requested_at' => Carbon::now(),
            ]);

            // Deduct amount from user balance and increment monthly count
            $user->decrement('balance', $request->amount);
            $user->incrementMonthlyWithdrawalCount();
            
            // Notify all admins about the new withdrawal request
            self::notifyAdminsOfWithdrawalRequest($user, $request->amount);
            
            // Send email to admin
            try {
                \Mail::to('earnquest82@gmail.com')->send(
                    new \App\Mail\AdminWithdrawalNotificationMail(
                        $user,
                        $request->amount,
                        $netAmount,
                        $feeAmount,
                        $user->bep20_address
                    )
                );
            } catch (\Exception $e) {
                // Log error but don't fail the withdrawal
                \Log::error('Failed to send withdrawal notification email to admin: ' . $e->getMessage());
            }
        });

        // Clear OTP verification cache
        Cache::forget("withdrawal_otp_verified_{$email}");

        return redirect()->route('dashboard')->with('success', 'Withdrawal request submitted successfully. It will be processed within 48 hours.');
    }

    public function bindWallet(Request $request)
    {
        $user = Auth::user();

        // Check if wallet is already bound
        if ($user->hasBoundWallet()) {
            return redirect()->route('withdrawal')->with('error', 'Your wallet is already bound. Contact support to update it.');
        }

        $request->validate([
            'bep20_address' => [
                'required',
                'string',
                'regex:/^0x[a-fA-F0-9]{40}$/',
            ],
        ]);

        $normalizedAddress = strtolower($request->bep20_address);

        // Check if address is already used by another user
        $existingUser = \App\Models\User::where('bep20_address', $normalizedAddress)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return redirect()->route('withdrawal')->withErrors(['bep20_address' => 'This wallet address is already bound to another account.']);
        }

        // Bind the wallet
        $user->bep20_address = $normalizedAddress;
        $user->wallet_bound_at = Carbon::now();
        $user->payment_method = 'bep20';
        $user->payment_details = $normalizedAddress;
        $user->save();

        return redirect()->route('withdrawal')->with('success', 'Wallet address bound successfully! You can now request withdrawals.');
    }

    public function history()
    {
        $user = Auth::user();
        $withdrawals = $user->withdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('withdrawal-history', compact('withdrawals'));
    }
}