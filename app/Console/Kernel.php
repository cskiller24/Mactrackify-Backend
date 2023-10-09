<?php

namespace App\Console;

use App\Models\TaskScheduler;
use Arr;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $tasks = TaskScheduler::all();

        $tasks->each(function ($task) use ($schedule) {
            $frequency = $task->frequency;

            $schedule->command($task->command, json_decode($task->arguments, true))->$frequency();
        });
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
