<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in routes
    }

    public function deposit(Request $request)
    {
        $user = Auth::user();

        if ($user->has_deposited) {
            return redirect()->route('dashboard')->with('error', 'You have already made your initial deposit.');
        }

        $packages = config('investment.packages', []);
        $packageCodes = array_keys($packages);

        $validated = $request->validate([
            'package_code' => ['required', Rule::in($packageCodes)],
            'wallet_address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $selectedPackage = $packages[$validated['package_code']];

        $walletAddress = strtolower($validated['wallet_address']);

        if ($user->hasBoundWallet() && strcasecmp($user->bep20_address, $walletAddress) !== 0) {
            return back()
                ->withErrors(['wallet_address' => 'You have already bound a different BEP20 wallet address. Please contact support to update it.'])
                ->withInput();
        }

        DB::transaction(function () use ($user, $validated, $selectedPackage, $walletAddress) {
            Deposit::create([
                'user_id' => $user->id,
                'amount' => $selectedPackage['deposit_amount'],
                'expected_withdrawal_cap' => $selectedPackage['withdrawal_cap'],
                'currency' => 'USD',
                'package_code' => $validated['package_code'],
                'payment_method' => 'bep20',
                'payment_details' => $walletAddress,
                'status' => 'pending',
                'notes' => $validated['transaction_reference']
                    ? 'TX Reference: ' . $validated['transaction_reference']
                    : 'Initial deposit request submitted',
            ]);

            $user->update([
                'payment_method' => 'bep20',
                'payment_details' => $walletAddress,
                'pending_deposit_amount' => $selectedPackage['deposit_amount'],
                'pending_package_code' => $validated['package_code'],
                'bep20_address' => $user->bep20_address ?? $walletAddress,
                'wallet_bound_at' => $user->wallet_bound_at ?? now(),
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Deposit request submitted successfully. Your account will be activated after payment verification.');
    }

    public function stripeWebhook(Request $request)
    {
        // Handle Stripe webhook for payment confirmation
        // This would typically involve verifying the webhook signature
        // and updating the deposit status
        
        return response()->json(['status' => 'success']);
    }
}