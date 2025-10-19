<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run the event risk detection command every day at midnight
        $schedule->command('events:detect-risks')
                ->dailyAt('00:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/event-risk-detection.log'));
                
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

