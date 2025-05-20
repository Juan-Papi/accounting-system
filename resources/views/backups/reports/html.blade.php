@extends('layouts.report')

@section('title', 'Reporte de Backups')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1>Reporte de Backups</h1>
                <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Tamaño</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $backup)
                            <tr>
                                <td>{{ $backup['name'] }}</td>
                                <td>{{ $backup['type'] }}</td>
                                <td>{{ round($backup['size'] / 1024, 2) }} KB</td>
                                <td>{{ $backup['date']->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay backups disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h3>Resumen</h3>
                <ul>
                    <li>Total de backups: {{ $backups->count() }}</li>
                    <li>Tamaño total: {{ round($backups->sum('size') / 1024 / 1024, 2) }} MB</li>
                    <li>Último backup: {{ $backups->first() ? $backups->first()['date']->format('d/m/Y H:i') : 'N/A' }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
