<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $backups = collect(Storage::files('backups'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::size($file),
                    'date' => Carbon::createFromTimestamp(Storage::lastModified($file)),
                    'type' => $this->getBackupType(basename($file))
                ];
            })
            ->sortByDesc('date');

        $config = BackupConfig::getSettings();

        return view('backups.index', compact('backups', 'config'));
    }

    public function createInstant()
    {
        try {
            Log::info('Iniciando creación de backup instantáneo.');

            // Ejecutar el comando de forma síncrona
            $exitCode = Artisan::call('db:backup');

            // Obtener el output del comando
            $output = Artisan::output();
            Log::info('Resultado del comando de backup: ' . $output);

            // Verificar si hubo error
            if ($exitCode !== 0) {
                Log::error('Error al crear backup instantáneo. Código: ' . $exitCode);
                return redirect()->route('backups.index')
                    ->with('error', 'Error al crear el backup: ' . trim($output));
            }

            // Extraer el nombre del archivo del output (asumiendo que el comando devuelve el nombre)
            $filename = trim($output);
            if (strpos($filename, 'exitosamente') !== false) {
                // Extraer solo el nombre de archivo
                preg_match('/Backup creado exitosamente: (.+)$/m', $filename, $matches);
                $filename = $matches[1] ?? '';
            }

            Log::info('Backup instantáneo creado exitosamente.', ['filename' => $filename]);
            return redirect()->route('backups.index')
                ->with('success', 'Backup instantáneo creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Excepción al crear el backup instantáneo.', ['exception' => $e->getMessage()]);
            return redirect()->route('backups.index')
                ->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }

    public function updateConfig(Request $request)
    {
        Log::info('Datos recibidos en la request 1:', $request->all());
        Log::info('Iniciando actualización de configuración de backups.');
        $validated = $request->validate([
            'frequency' => 'nullable|in:daily,weekly,monthly',
            'time' => 'nullable|date_format:H:i',
        ]);
        Log::info('Datos recibidos en la request:', $request->all());

        // El checkbox enabled solo envía un valor cuando está marcado
        $enabled = $request->has('enabled');

        // Obtener la configuración actual
        $config = BackupConfig::getSettings();

        // Actualizar los valores
        $config->enabled = $enabled;
        $config->frequency = $request->input('frequency', $config->frequency);
        $config->time = $request->input('time', $config->time);

        // Log para depuración
        Log::debug('Datos a actualizar:', [
            'enabled' => $config->enabled,
            'frequency' => $config->frequency,
            'time' => $config->time
        ]);

        if (!$config->save()) {
            Log::error('Error al guardar la configuración');
            return back()->with('error', 'Error al guardar la configuración');
        }

        return redirect()->route('backups.index')
            ->with('success', 'Configuración de backups actualizada correctamente');
    }

    public function download($filename)
    {
        if (!Storage::exists('backups/' . $filename)) {
            abort(404);
        }

        return Storage::download('backups/' . $filename);
    }

    private function getBackupType($filename)
    {
        if (str_starts_with($filename, 'daily-')) return 'Diario';
        if (str_starts_with($filename, 'weekly-')) return 'Semanal';
        if (str_starts_with($filename, 'monthly-')) return 'Mensual';
        return 'Instantáneo';
    }
}
