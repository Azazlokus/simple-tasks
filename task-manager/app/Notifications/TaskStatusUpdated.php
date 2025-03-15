<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Task;

class TaskStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable): array
    {
        return ['database']; 
    }

    public function toDatabase($notifiable): array
    {
        Log::channel('notifications')->info("Task #{$this->task->id} was updated to status: " . $this->task->status->value);

        return [
            'message' => "Задача #{$this->task->id} была переведена в статус {$this->task->status->value}.",
            'task_id' => $this->task->id,
            'status' => $this->task->status,
        ];
    }

}
