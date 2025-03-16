<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $tasks = Task::all();

        if ($users->isEmpty() || $tasks->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            if (rand(1, 100) <= 60) {
                $randomTasks = $tasks->random(rand(1, min(5, $tasks->count())))->pluck('id');
                $user->tasks()->attach($randomTasks);
            }
        }
    }
}