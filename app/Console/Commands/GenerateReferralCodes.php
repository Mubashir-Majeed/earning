<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateReferralCodes extends Command
{
    protected $signature = 'referrals:generate-codes';
    protected $description = 'Generate referral codes for existing users';

    public function handle()
    {
        $users = User::whereNull('referral_code')->get();
        
        $this->info("Found {$users->count()} users without referral codes");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        
        foreach ($users as $user) {
            $user->referral_code = User::generateReferralCode();
            $user->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('All referral codes generated successfully!');
        
        // Show some examples
        $this->newLine();
        $this->info('Sample referral codes:');
        User::whereNotNull('referral_code')->take(5)->get()->each(function($user) {
            $this->line("User: {$user->name} - Code: {$user->referral_code}");
        });
    }
}
