<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignTasksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tasks = Task::doesntHave('users')->get();
        $users = User::where('status', 'active')->get();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($tasks as $task) {
            $randomUser = $users->random();
            $task->users()->attach($randomUser->id);
        }
    }
}