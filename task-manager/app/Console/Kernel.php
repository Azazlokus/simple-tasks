<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Добавляем запуск задачи AssignTasksJob каждые 2 минуты
        $schedule->job(new \App\Jobs\AssignTasksJob())->everyTwoMinutes();
    }

    /**
     * Register the application's command events.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}