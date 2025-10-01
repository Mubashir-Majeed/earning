<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VideoEarningService;
use App\Models\User;

class AssignDailyTasks extends Command
{
    protected $signature = 'tasks:assign-daily';
    protected $description = 'Assign daily video tasks to all eligible users';

    public function handle()
    {
        $service = new VideoEarningService();
        $users = User::where('has_deposited', true)->get();
        
        $assigned = 0;
        foreach ($users as $user) {
            $service->assignDailyTasks($user);
            $assigned++;
        }
        
        $this->info("Assigned daily tasks to {$assigned} users");
        return 0;
    }
}
