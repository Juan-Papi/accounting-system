<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DbBackupCommand extends Command
{
    protected $signature = 'db:backup {--frequency= : Optional frequency (daily, weekly, monthly)}';
    protected $description = 'Create a database backup';

    public function handle()
    {
        date_default_timezone_set('America/La_Paz');
        $now = Carbon::now('America/La_Paz')->format('Y-m-d-H-i-s');

        try {
            $this->info('Iniciando proceso de backup...');
            Log::info('Iniciando proceso de backup desde comando Artisan');

            // Ensure backup directory exists
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            // Generate filename
            //$now = now()->format('Y-m-d-H-i-s');

            if ($frequency = $this->option('frequency')) {
                $filename = 'auto-' . $frequency . '-' . $now;
            } else {
                $filename = 'manual-' . $now;
            }

            $filename .= '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Informar progreso
            $this->info('Generando backup en: ' . $path);
            Log::info('Generando backup en: ' . $path);

            // Build the mysqldump command - fixed password escaping
            $dbUsername = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');
            $dbName = config('database.connections.mysql.database');

            // Handle password escaping properly - this is critical
            $passwordArg = !empty($dbPassword) ? "-p" . escapeshellarg($dbPassword) : "";

            $command = sprintf(
                'mysqldump -u%s %s %s > %s',
                escapeshellarg($dbUsername),
                $passwordArg,
                escapeshellarg($dbName),
                escapeshellarg($path)
            );

            // Log that we're about to execute the command (without password)
            $this->info('Ejecutando mysqldump...');
            Log::info('Ejecutando comando mysqldump');

            // Execute the mysqldump command
            $output = [];
            $returnVar = null;
            exec($command, $output, $returnVar);

            // Check for errors
            if ($returnVar !== 0) {
                $errorMsg = 'Error al ejecutar mysqldump. Código de retorno: ' . $returnVar;
                Log::error($errorMsg);
                $this->error($errorMsg);
                return false;
            }

            // Check if file was created and has content
            if (!file_exists($path) || filesize($path) == 0) {
                $errorMsg = 'El archivo de backup no fue creado o está vacío en: ' . $path;
                Log::error($errorMsg);
                $this->error($errorMsg);
                return false;
            }

            // Success
            $successMsg = "Backup creado exitosamente: {$filename}";
            Log::info($successMsg);
            $this->info($successMsg);

            return $filename;
        } catch (\Exception $e) {
            $errorMsg = "Error al crear backup: " . $e->getMessage();
            Log::error($errorMsg, ['exception' => $e]);
            $this->error($errorMsg);
            return false;
        }
    }
}
