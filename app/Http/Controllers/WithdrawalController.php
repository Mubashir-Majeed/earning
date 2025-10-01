<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in routes
    }

    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'withdrawal_method' => 'required|string|in:bank_transfer,paypal,crypto',
            'withdrawal_details' => 'required|string',
        ]);

        $user = Auth::user();

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
        $feePercentage = $user->withdrawalFeePercent();
        $feeAmount = $request->amount * $feePercentage;
        $netAmount = $request->amount - $feeAmount;

        DB::transaction(function () use ($user, $request, $feeAmount, $netAmount) {
            // Create withdrawal record
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'fee_amount' => $feeAmount,
                'net_amount' => $netAmount,
                'currency' => 'USD',
                'withdrawal_method' => $request->withdrawal_method,
                'withdrawal_details' => $request->withdrawal_details,
                'status' => 'pending',
                'requested_at' => Carbon::now(),
            ]);

            // Deduct amount from user balance and increment monthly count
            $user->decrement('balance', $request->amount);
            $user->incrementMonthlyWithdrawalCount();
        });

        return redirect()->route('dashboard')->with('success', 'Withdrawal request submitted successfully. It will be processed within 48 hours.');
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