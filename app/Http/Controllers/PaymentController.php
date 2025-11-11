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
            'wallet_address' => ['nullable', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'transaction_reference' => ['required', 'string', 'max:255'],
            'transaction_receipt' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $selectedPackage = $packages[$validated['package_code']];

        $requestedWallet = $validated['wallet_address'] ?? null;
        $walletAddress = null;

        if ($user->hasBoundWallet()) {
            $walletAddress = strtolower($user->bep20_address);

            if ($requestedWallet && strcasecmp($user->bep20_address, $requestedWallet) !== 0) {
                return back()
                    ->withErrors(['wallet_address' => 'You have already bound a different BEP20 wallet address. Please contact support to update it.'])
                    ->withInput();
            }
        } elseif ($requestedWallet) {
            $walletAddress = strtolower($requestedWallet);
        }

        if ($user->deposits()->where('status', 'pending')->exists()) {
            return redirect()->route('deposit')->with('info', 'Your previous deposit request is still under review.');
        }

        $receiptPath = null;
        if ($request->hasFile('transaction_receipt')) {
            $receiptPath = $request->file('transaction_receipt')->store('deposit-receipts', 'public');
        }

        DB::transaction(function () use ($user, $validated, $selectedPackage, $walletAddress, $receiptPath) {
            Deposit::create([
                'user_id' => $user->id,
                'amount' => $selectedPackage['deposit_amount'],
                'expected_withdrawal_cap' => $selectedPackage['withdrawal_cap'],
                'currency' => 'USD',
                'package_code' => $validated['package_code'],
                'payment_method' => 'bep20',
                'payment_id' => $validated['transaction_reference'],
                'payment_details' => $walletAddress,
                'status' => 'pending',
                'notes' => null,
                'receipt_path' => $receiptPath,
            ]);

            $userUpdate = [
                'payment_method' => 'bep20',
                'pending_deposit_amount' => $selectedPackage['deposit_amount'],
                'pending_package_code' => $validated['package_code'],
            ];

            if ($walletAddress) {
                $userUpdate['payment_details'] = $walletAddress;
                $userUpdate['bep20_address'] = $user->bep20_address ?? $walletAddress;
                $userUpdate['wallet_bound_at'] = $user->wallet_bound_at ?? now();
            }

            $user->update($userUpdate);
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