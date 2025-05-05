<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
            $filename = Artisan::call('db:backup');
            Log::info('Paso el artisan');
            if ($filename) {
                Log::info('Backup instantáneo creado exitosamente.', ['filename' => $filename]);
                return redirect()->route('backups.index')
                    ->with('success', 'Backup instantáneo creado exitosamente');
            }

            Log::error('Error al crear el backup instantáneo: el comando Artisan no devolvió un nombre de archivo.');
            return redirect()->route('backups.index')
                ->with('error', 'Error al crear el backup');
        } catch (\Exception $e) {
            Log::error('Excepción al crear el backup instantáneo.', ['exception' => $e->getMessage()]);
            return redirect()->route('backups.index')
                ->with('error', 'Ocurrió un error inesperado al crear el backup');
        }
    }

    public function updateConfig(Request $request)
    {
        $validated = $request->validate([
            'frequency' => 'required_if:enabled,true|in:daily,weekly,monthly',
            'time' => 'required_if:enabled,true|date_format:H:i',
            'enabled' => 'boolean'
        ]);

        // Si se desactiva, limpiar la frecuencia
        if (empty($validated['enabled'])) {
            $validated['frequency'] = null;
        }

        $config = BackupConfig::getSettings();
        $config->update($validated);

        return redirect()->route('backups.index')
            ->with('success', 'Configuración de backups actualizada');
    }

    public function download($filename)
    {
        if (!Storage::exists('backups/' . $filename)) {
            abort(404);
        }

        return Storage::download('backups/' . $filename);
    }

    public function destroy($filename)
    {
        Storage::delete('backups/' . $filename);
        return redirect()->route('backups.index')
            ->with('success', 'Backup eliminado correctamente');
    }

    private function getBackupType($filename)
    {
        if (str_starts_with($filename, 'daily-')) return 'Diario';
        if (str_starts_with($filename, 'weekly-')) return 'Semanal';
        if (str_starts_with($filename, 'monthly-')) return 'Mensual';
        return 'Instantáneo';
    }
}
