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
        // Generate daily quests from templates every day at midnight
        $schedule->command('quests:generate')
                 ->dailyAt('00:00')
                 ->withoutOverlapping()
                 ->onSuccess(function () {
                     \Log::info('Daily quests generated successfully');
                 })
                 ->onFailure(function () {
                     \Log::error('Failed to generate daily quests');
                 });

        // Alternative: Run every day at specific time
        // $schedule->command('quests:generate')->daily();
        // $schedule->command('quests:generate')->everyMinute(); // For testing
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
