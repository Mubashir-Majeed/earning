<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in routes
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string|in:stripe,paypal,bank_transfer,crypto',
            'payment_details' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user->has_deposited) {
            return redirect()->route('dashboard')->with('error', 'You have already made your initial deposit.');
        }

        DB::transaction(function () use ($user, $request) {
            // Create deposit record
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'currency' => 'USD',
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details ?? $request->payment_method,
                'status' => 'pending',
                'notes' => 'Initial deposit for account activation',
            ]);

            // Update user status
            $user->update([
                'has_deposited' => true,
                'initial_deposit_amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
            ]);

            // If user has a referrer, increment their referrals_count
            if ($user->referrer_id) {
                $user->referrer()->increment('referrals_count');
            }
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