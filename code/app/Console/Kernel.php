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
        // $schedule->command('inspire')->hourly();

        $schedule->command('tasks:notify-overdue')
            ->daily()
            ->at('09:00')
            ->timezone('Europe/Paris');

        $schedule->command('time:calculate-totals')
            ->hourly();

        $schedule->command('project:cleanup-completed')
            ->weekly()
            ->sundays()
            ->at('02:00');

        $schedule->command('reports:generate-daily')
            ->daily()
            ->at('23:30');
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