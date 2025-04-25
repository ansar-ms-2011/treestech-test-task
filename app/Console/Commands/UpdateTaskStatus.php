<?php

namespace App\Console\Commands;

use App\Events\TaskStatusUpdated;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTaskStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-task-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will update the status of tasks based on their due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        Log::info("Today is: " . $today);
        
        // Fetch tasks that are pending and due today
        // and update their status to 'in_progress'
        $pending_tasks = Task::where('status', 'pending')
            ->where('due_date', $today)
            ->get();

        foreach ($pending_tasks as $task) {
            $task->status = 'in_progress';
            $task->save();
        }

        $this->info("Updated " . $pending_tasks->count() . " tasks to 'in_progress'");

        // Fetch tasks that are in_progress and due_date has passes
        // and update their status to 'completed'
        $in_progress_tasks = Task::where('status', 'in_progress')
            ->where('due_date', '<', $today)
            ->get();

        foreach ($in_progress_tasks as $task) {
            $task->status = 'completed';
            $task->save();
        }

        $this->info("Updated " . $in_progress_tasks->count() . " tasks to 'completed'");
        if($pending_tasks->count() > 0 || $in_progress_tasks->count() > 0){
            broadcast(new TaskStatusUpdated($task))->toOthers();
        }
    }
}
