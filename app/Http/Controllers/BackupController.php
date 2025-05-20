<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BackupsExport;
use PDF;

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
        $enabled = $request->has('enabled');
        if ($enabled) {
            Log::info('Valor de "frequency" en la request:', ['frequency' => $request->frequency]);
            Log::info('Valor de "time" en la request:', ['time' => $request->time]);
        }
        Log::info('Estado de "enabled": ' . ($enabled ? 'true' : 'false'));
        $rules = [
            'frequency' => $enabled ? 'required|in:daily,weekly,monthly' : 'nullable|in:daily,weekly,monthly',
            'time' => $enabled ? 'required|date_format:H:i,H:i:s' : 'nullable|date_format:H:i,H:i:s',
        ];

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación al actualizar la configuración de backups.', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        }
        Log::info('Paso la validación de la configuración de backups.');
        if ($enabled) {
            Log::info('Valor de "frequency" en la request:', ['frequency' => $request->frequency]);
            Log::info('Valor de "time" en la request:', ['time' => $request->time]);
        }
        Log::info('Datos recibidos en la request:', $request->all());

        // El checkbox enabled solo envía un valor cuando está marcado
        $enabled = $request->has('enabled');

        // Obtener la configuración actual
        $config = BackupConfig::getSettings();
        $config->time = substr($request->input('time', $config->time), 0, 5);

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

    public function generateHtmlReport()
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

        $html = view('backups.reports.html', compact('backups'))->render();

        $filename = 'reporte-backups-' . date('Y-m-d-H-i-s') . '.html';
        Storage::put('reports/' . $filename, $html);

        return Storage::download('reports/' . $filename);
    }

    public function generatePdfReport()
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

        $pdf = PDF::loadView('backups.reports.pdf', compact('backups'));

        return $pdf->download('reporte-backups-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    public function generateCsvReport()
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

        // Crear el contenido CSV
        $headers = ['Nombre del Archivo', 'Tipo de Backup', 'Tamaño', 'Fecha de Creación'];
        $csvContent = implode(',', $headers) . "\n";

        foreach ($backups as $backup) {
            $size = round($backup['size'] / 1024, 2) . ' KB';
            $date = $backup['date']->format('d/m/Y H:i');

            // Escapar campos que contienen comas y poner comillas alrededor
            $row = [
                $this->csvEscape($backup['name']),
                $this->csvEscape($backup['type']),
                $this->csvEscape($size),
                $this->csvEscape($date)
            ];

            $csvContent .= implode(',', $row) . "\n";
        }

        // Crear una respuesta para descargar
        $filename = 'reporte-backups-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response($csvContent, 200, $headers);
    }

    /**
     * Escapa un valor para usarlo en CSV
     */
    private function csvEscape($value)
    {
        // Si el valor contiene comillas, comas o saltos de línea, encerrarlo en comillas
        // y duplicar las comillas dentro del valor
        if (preg_match('/[",\r\n]/', $value)) {
            return '"' . str_replace('"', '""', $value) . '"';
        }

        return $value;
    }

    private function getBackupType($filename)
    {
        if (str_starts_with($filename, 'daily-')) return 'Diario';
        if (str_starts_with($filename, 'weekly-')) return 'Semanal';
        if (str_starts_with($filename, 'monthly-')) return 'Mensual';
        return 'Instantáneo';
    }
}
