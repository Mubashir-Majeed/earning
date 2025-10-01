<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Withdrawal;

class ListWithdrawals extends Command
{
    protected $signature = 'withdrawal:list';
    protected $description = 'List all withdrawals';

    public function handle()
    {
        $withdrawals = Withdrawal::with('user')->get();
        
        if ($withdrawals->isEmpty()) {
            $this->info('No withdrawals found.');
            return;
        }

        $this->info('Withdrawals:');
        $this->newLine();
        
        foreach ($withdrawals as $withdrawal) {
            $this->line("ID: {$withdrawal->id}");
            $this->line("User: {$withdrawal->user->name}");
            $this->line("Status: {$withdrawal->status}");
            $this->line("Amount: $" . number_format($withdrawal->amount, 2));
            $this->line("Net: $" . number_format($withdrawal->net_amount, 2));
            $this->line("Method: {$withdrawal->withdrawal_method}");
            $this->line("Requested: {$withdrawal->requested_at}");
            $this->newLine();
        }
    }
}
