<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $user = Auth::user();

        $minWithdrawal = config('platform.min_withdrawal', 10);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $minWithdrawal],
        ]);

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
            Withdrawal::create([
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
        });

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