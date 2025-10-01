<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestWithdrawal extends Command
{
    protected $signature = 'withdrawal:test {user_id}';
    protected $description = 'Test withdrawal requirements for a user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $this->info("Testing withdrawal requirements for: {$user->name}");
        $this->newLine();
        
        $this->line("✅ Has deposited: " . ($user->has_deposited ? 'Yes' : 'No'));
        $this->line("💰 Balance: $" . number_format($user->balance, 2));
        $this->line("👥 Referrals: {$user->referrals_count}");
        $this->line("📊 Level: {$user->level}");
        $this->line("🔒 Can withdraw: " . ($user->canWithdraw() ? 'Yes' : 'No'));
        
        $this->newLine();
        
        // Check individual requirements
        $this->line("Individual Requirements:");
        $this->line("- Minimum balance ($10): " . ($user->balance >= 10 ? '✅' : '❌'));
        $this->line("- Referral requirement: " . ($user->meetsReferralRequirementForWithdrawal() ? '✅' : '❌'));
        $this->line("- Monthly quota: " . ($user->withinMonthlyWithdrawalQuota() ? '✅' : '❌'));
        $this->line("- Initial deposit: " . ($user->has_deposited ? '✅' : '❌'));
        
        $this->newLine();
        
        if ($user->canWithdraw()) {
            $this->info("🎉 User can make withdrawal requests!");
        } else {
            $this->warn("⚠️  User cannot withdraw - requirements not met.");
        }
    }
}
