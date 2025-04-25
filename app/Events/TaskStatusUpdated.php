<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdated implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $task;
   
    public function __construct(Task $task)
    {
        $this->task = $task;
    }


    public function broadcastOn(): array
    {
        return ['treestech-test-task'];
    }

    public function broadcastAs()
    {
        return 'status.updated';
    }
}
