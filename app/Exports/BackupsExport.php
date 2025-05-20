<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class BackupsExport implements FromArray, WithHeadings, WithStyles
{
    protected $backups;

    public function __construct(array $backups)
    {
        $this->backups = $backups;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $data = [];

        foreach ($this->backups as $backup) {
            $data[] = [
                $backup['name'],
                $backup['type'],
                round($backup['size'] / 1024, 2) . ' KB',
                $backup['date'] instanceof Carbon
                    ? $backup['date']->format('d/m/Y H:i')
                    : $backup['date'],
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nombre del Archivo',
            'Tipo de Backup',
            'Tamaño',
            'Fecha de Creación'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
