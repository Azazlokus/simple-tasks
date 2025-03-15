<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AssignUnassignedTasksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Log::info('AssignUnassignedTasksJob запустился');
        $tasks = Task::doesntHave('users')->get();
        $users = User::inRandomOrder()->get();
        Log::info("Trying to assign users to unassigned tasks. Found {$tasks->count()} tasks and {$users->count()} users.");

        if ($tasks->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($tasks as $task) {
            $randomUser = $users->random();
            $task->users()->attach($randomUser->id);
            Log::info("Задача ID {$task->id} назначена пользователю ID {$randomUser->id}");
        }
    }
}