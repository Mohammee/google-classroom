<?php

namespace App\Console;

use App\Jobs\SendClassroomNotification;
use App\Models\Classwork;
use App\Notifications\NewClassroomNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $classwork = Classwork::first();
        $schedule->command('app:message mohammad')->everyMinute();
        $schedule->job(
            new SendClassroomNotification(
                $classwork->users,
                new NewClassroomNotification($classwork)
            )
        )->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
