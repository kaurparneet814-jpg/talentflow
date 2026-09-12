<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TechnicalTask;

class MarkOverdueTechnicalTasks extends Command
{
    // Command used to mark expired technical tasks as overdue.
    protected $signature = 'tasks:mark-overdue';

    // Description shown in the Artisan command list.
    protected $description = 'Mark expired technical tasks as overdue';

    public function handle()
    {
        // Update incomplete tasks whose deadline has passed.
        $updatedTasks = TechnicalTask::whereIn('status', [
                'pending',
                'in_progress'
            ])
            ->where('deadline', '<', now())
            ->update([
                'status' => 'overdue'
            ]);

        // Show how many tasks were updated.
        $this->info("$updatedTasks technical task(s) marked as overdue.");

        return Command::SUCCESS;
    }
}
