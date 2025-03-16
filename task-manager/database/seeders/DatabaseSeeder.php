<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory(20)->create()->each(function ($user) {
            $roles = Role::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $user->roles()->attach($roles);
        });
        
        Task::factory(200)->create();
        
        $this->call(TaskUserSeeder::class);
    }
}
