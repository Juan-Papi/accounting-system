<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'db:backup {--frequency=}';
    protected $description = 'Create a database backup';

    public function handle()
    {
        try {
            // Crear directorio si no existe
            Storage::makeDirectory('backups');

            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s');

            // Agregar prefijo según frecuencia si está presente
            // if ($frequency = $this->option('frequency')) {
            //     $filename = $frequency . '-' . $filename;
            // }

            if ($frequency = $this->option('frequency')) {
                $filename = 'auto-' . $frequency . '-' . $filename;
            } else {
                $filename = 'manual-' . $filename;
            }

            $filename .= '.sql';
            $path = storage_path('app/backups/' . $filename);

            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Error al ejecutar mysqldump');
            }

            $this->info("Backup creado exitosamente: {$filename}");
            return $filename;
        } catch (\Exception $e) {
            $this->error("Error al crear backup: " . $e->getMessage());
            return false;
        }
    }
}
