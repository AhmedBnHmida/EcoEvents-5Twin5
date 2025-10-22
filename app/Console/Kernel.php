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

        // Update event statuses every 5 minutes (more frequent for real-time updates)
        $schedule->command('events:update-statuses')
                ->everyFiveMinutes()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/event-status-updates.log'));

        // More frequent checks during business hours (8 AM to 8 PM)
        $schedule->command('events:update-statuses')
                ->everyMinute()
                ->between('8:00', '20:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/event-status-updates-minute.log'));

        // Clean up old logs weekly
        $schedule->command('log:clear --keep-last=7') // Keep last 7 days of logs
                ->weekly()
                ->sundays()
                ->at('01:00');
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