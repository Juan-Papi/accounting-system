<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\BackupConfig;
use App\Console\Commands\DbBackupCommand;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        DbBackupCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Obtener configuración de backups
        try {
            $config = BackupConfig::getSettings();

            // Si los backups automáticos están habilitados
            if ($config->enabled) {
                Log::info('Programando backup automático', [
                    'frequency' => $config->frequency,
                    'time' => $config->time
                ]);

                $command = $schedule->command('db:backup --frequency=' . $config->frequency);

                // Programar según la frecuencia
                switch ($config->frequency) {
                    case 'daily':
                        $command->dailyAt($config->time);
                        break;
                    case 'weekly':
                        $command->weeklyOn(1, $config->time); // 1 = Lunes
                        break;
                    case 'monthly':
                        $command->monthlyOn(1, $config->time); // Día 1 de cada mes
                        break;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error al configurar el scheduler de backups', [
                'exception' => $e->getMessage()
            ]);
        }
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
