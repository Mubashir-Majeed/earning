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

        $packages = config('investment.packages', []);
        $packageCodes = array_keys($packages);
        
        // For redeposit, only allow higher packages
        if ($user->has_deposited && $user->investment_package) {
            $currentPackageCode = $user->investment_package;
            $packageOrder = ['starter_35', 'growth_50', 'pro_100'];
            $currentIndex = array_search($currentPackageCode, $packageOrder);
            
            if ($currentIndex !== false) {
                // Only allow next 2 packages
                $allowedUpgrades = array_slice($packageOrder, $currentIndex + 1, 2);
                $packageCodes = array_intersect($packageCodes, $allowedUpgrades);
                
                if (empty($packageCodes)) {
                    return redirect()->route('deposit')->with('error', 'No upgrade packages available. You already have the highest package.');
                }
            }
        } elseif ($user->has_deposited) {
            // User has deposited but no package set (shouldn't happen, but handle it)
            return redirect()->route('dashboard')->with('error', 'Please contact support to resolve your account status.');
        }

        $validated = $request->validate([
            'package_code' => ['required', Rule::in($packageCodes)],
            'wallet_address' => ['nullable', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'transaction_reference' => ['required', 'string', 'max:255'],
            'transaction_receipt' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $selectedPackage = $packages[$validated['package_code']];
        
        // Calculate deposit amount (difference for redeposit, full amount for new deposit)
        $isRedeposit = $user->has_deposited && $user->investment_package;
        $depositAmount = $selectedPackage['deposit_amount'];
        
        if ($isRedeposit) {
            $currentPackage = $packages[$user->investment_package] ?? null;
            if ($currentPackage) {
                $depositAmount = $selectedPackage['deposit_amount'] - $currentPackage['deposit_amount'];
            }
        }

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

        DB::transaction(function () use ($user, $validated, $selectedPackage, $walletAddress, $receiptPath, $depositAmount, $isRedeposit) {
            Deposit::create([
                'user_id' => $user->id,
                'amount' => $depositAmount,
                'expected_withdrawal_cap' => $selectedPackage['withdrawal_cap'],
                'currency' => 'USD',
                'package_code' => $validated['package_code'],
                'payment_method' => 'bep20',
                'payment_id' => $validated['transaction_reference'],
                'payment_details' => $walletAddress,
                'status' => 'pending',
                'notes' => $isRedeposit ? 'Package upgrade deposit' : null,
                'receipt_path' => $receiptPath,
            ]);

            $userUpdate = [
                'payment_method' => 'bep20',
                'pending_deposit_amount' => $depositAmount,
                'pending_package_code' => $validated['package_code'],
            ];

            if ($walletAddress) {
                $userUpdate['payment_details'] = $walletAddress;
                $userUpdate['bep20_address'] = $user->bep20_address ?? $walletAddress;
                $userUpdate['wallet_bound_at'] = $user->wallet_bound_at ?? now();
            }

            $user->update($userUpdate);
            
            // Notify all admins about the new deposit request
            $packageName = $selectedPackage['name'] ?? 'Package';
            $notificationAmount = $isRedeposit ? $depositAmount : $selectedPackage['deposit_amount'];
            \App\Traits\CreatesNotifications::notifyAdminsOfDepositRequest($user, $notificationAmount, $packageName);
        });

        $successMessage = $isRedeposit 
            ? 'Upgrade deposit request submitted successfully. Your package will be upgraded after payment verification.'
            : 'Deposit request submitted successfully. Your account will be activated after payment verification.';

        return redirect()->route('dashboard')->with('success', $successMessage);
    }

    public function stripeWebhook(Request $request)
    {
        // Handle Stripe webhook for payment confirmation
        // This would typically involve verifying the webhook signature
        // and updating the deposit status
        
        return response()->json(['status' => 'success']);
    }
}