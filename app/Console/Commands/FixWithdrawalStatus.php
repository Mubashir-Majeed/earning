<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Withdrawal;

class FixWithdrawalStatus extends Command
{
    protected $signature = 'withdrawal:fix-status {withdrawal_id}';
    protected $description = 'Fix withdrawal status to completed';

    public function handle()
    {
        $withdrawalId = $this->argument('withdrawal_id');
        $withdrawal = Withdrawal::find($withdrawalId);
        
        if (!$withdrawal) {
            $this->error("Withdrawal with ID {$withdrawalId} not found.");
            return;
        }

        $this->info("Current status: {$withdrawal->status}");
        
        $withdrawal->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->info("Withdrawal status updated to: completed");
        $this->info("User: {$withdrawal->user->name}");
        $this->info("Amount: $" . number_format($withdrawal->amount, 2));
        $this->info("Net Amount: $" . number_format($withdrawal->net_amount, 2));
    }
}
