<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckReferrals extends Command
{
    protected $signature = 'referrals:check';
    protected $description = 'Check all referral relationships';

    public function handle()
    {
        $users = User::with('referrer')->whereNotNull('referrer_id')->get();
        
        $this->info("Found {$users->count()} users with referrers:");
        $this->newLine();
        
        foreach ($users as $user) {
            $this->line("User: {$user->name} (ID: {$user->id})");
            $this->line("  - Referrer: {$user->referrer->name} (ID: {$user->referrer->id})");
            $this->line("  - Deposited: " . ($user->has_deposited ? 'Yes' : 'No'));
            $this->line("  - Referral Code: {$user->referral_code}");
            $this->line("  - Referrer Code: {$user->referrer->referral_code}");
            $this->newLine();
        }
    }
}
