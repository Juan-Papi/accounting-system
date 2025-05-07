<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\BackupConfig;
//use App\Console\Commands\DbBackupCommand;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        // DbBackupCommand::class,
        \App\Console\Commands\DatabaseBackupCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Obtener configuración de backups
        try {
            $config = BackupConfig::getSettings();
            $config->refresh(); // Asegurarse de obtener los datos más recientes de la base de datos

            // Si los backups automáticos están habilitados
            if ($config->enabled) {
                Log::info('Programando backup automático', [
                    'frequency' => $config->frequency,
                    'time' => $config->time
                ]);

                $command = $schedule->command('db:backup --frequency=' . $config->frequency);

                $timeWithoutSeconds = substr($config->time, 0, 5);
                // Programar según la frecuencia
                switch ($config->frequency) {
                    case 'daily':
                        $command->dailyAt($timeWithoutSeconds);
                        Log::info('Copia de seguridad diaria', ['time' => $config->time]);
                        break;
                    case 'weekly':
                        $command->weeklyOn(1, $timeWithoutSeconds); // 1 = Lunes
                        Log::info('Copia de seguridad semanal');
                        break;
                    case 'monthly':
                        $command->monthlyOn(1, $timeWithoutSeconds); // Día 1 de cada mes
                        Log::info('Copia de seguridad mensual');
                        break;
                }
            }
            $command->timezone('America/La_Paz')->appendOutputTo(storage_path('logs/backups.log'));
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
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
