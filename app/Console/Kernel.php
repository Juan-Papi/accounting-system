<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\BackupConfig;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $config = BackupConfig::getSettings();

        if (!$config->enabled) {
            return;
        }

        $command = $schedule->command('db:backup', [
            '--frequency' => $config->frequency
        ]);

        switch ($config->frequency) {
            case 'daily':
                $command->dailyAt($config->time);
                break;
            case 'weekly':
                $command->weeklyOn(1, $config->time); // Lunes
                break;
            case 'monthly':
                $command->monthlyOn(1, $config->time); // Primer día del mes
                break;
        }
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
