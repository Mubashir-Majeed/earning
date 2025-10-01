<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserEarning;

class FixReferralBonus extends Command
{
    protected $signature = 'referrals:fix-bonus {user_id}';
    protected $description = 'Fix referral bonus for a specific user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        if (!$user->referrer_id) {
            $this->error("User {$user->name} doesn't have a referrer.");
            return;
        }

        if (!$user->has_deposited) {
            $this->error("User {$user->name} hasn't made a deposit yet.");
            return;
        }

        $referrer = $user->referrer;
        
        // Check if bonus already awarded
        $existingBonus = UserEarning::where('user_id', $referrer->id)
            ->where('type', 'referral')
            ->where('description', 'like', "%{$user->name}%")
            ->first();

        if ($existingBonus) {
            $this->error("Referral bonus already awarded for {$user->name}.");
            return;
        }

        // Award the bonus
        $referrer->increment('balance', 5.00);
        $referrer->increment('points', 100);
        $referrer->increment('referrals_count');

        // Create earning record
        UserEarning::create([
            'user_id' => $referrer->id,
            'points_earned' => 100,
            'dollar_value' => 5.00,
            'type' => 'referral',
            'description' => "Referral bonus for {$user->name}",
            'earned_date' => now()->toDateString(),
        ]);

        $this->info("Referral bonus awarded successfully!");
        $this->info("Referrer: {$referrer->name} ({$referrer->referral_code})");
        $this->info("Referred: {$user->name} ({$user->referral_code})");
        $this->info("Bonus: $5.00 + 100 points");
    }
}
