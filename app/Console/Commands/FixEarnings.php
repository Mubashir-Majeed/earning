<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserEarning;

class FixEarnings extends Command
{
    protected $signature = 'earnings:fix';
    protected $description = 'Fix earnings with zero dollar values';

    public function handle()
    {
        $earnings = UserEarning::where('dollar_value', 0)->get();
        
        $this->info("Found {$earnings->count()} earnings with zero dollar value");
        
        foreach ($earnings as $earning) {
            $dollarValue = $earning->points_earned * 0.001; // 1000 points = $1
            $earning->update(['dollar_value' => $dollarValue]);
            $this->line("Fixed earning ID {$earning->id}: {$earning->points_earned} points = \${$dollarValue}");
        }
        
        $this->info('All earnings fixed!');
    }
}
