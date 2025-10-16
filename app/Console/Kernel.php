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
        // Envoyer les rappels 24 heures avant les événements
        $schedule->command('events:send-reminders --hours=24')
                ->dailyAt('09:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/reminders.log'));
        
        // Vérifier les événements récemment terminés et envoyer des remerciements
        $schedule->command('events:send-thank-you')
                ->dailyAt('10:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/thank-you.log'));
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
