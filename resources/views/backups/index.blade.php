@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <br>
@stop

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Gestión de Backups</h1>
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group">
                    <form id="backupForm" action="{{ route('backups.create-instant') }}" method="POST">
                        @csrf
                        <button id="backupButton" type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-plus"></i> Backup Instantáneo
                        </button>
                    </form>

                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="reportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-file-export"></i> Exportar Reporte
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="reportDropdown">
                            <a class="dropdown-item" href="{{ route('backups.report.html') }}">
                                <i class="fas fa-file-code text-info"></i> HTML
                            </a>
                            <a class="dropdown-item" href="{{ route('backups.report.pdf') }}">
                                <i class="fas fa-file-pdf text-danger"></i> PDF
                            </a>
                            <a class="dropdown-item" href="{{ route('backups.report.csv') }}">
                                <i class="fas fa-file-csv text-success"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de alerta -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Modal de carga -->
        <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <h4>Creando backup</h4>
                        <p class="mb-0">Por favor espere mientras se crea el backup de la base de datos. Este proceso
                            puede tomar varios minutos dependiendo del tamaño de su base de datos.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Configuración Automática</div>
            <div class="card-body">
                <form method="POST" action="{{ route('backups.update-config') }}">
                    @csrf
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="enabled" name="enabled" value="1"
                                {{ $config->enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="enabled">
                                Habilitar backups automáticos
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="frequency">Frecuencia</label>
                            <select name="frequency" id="frequency" class="form-control">
                                <option value="daily" {{ $config->frequency == 'daily' ? 'selected' : '' }}>Diario</option>
                                <option value="weekly" {{ $config->frequency == 'weekly' ? 'selected' : '' }}>Semanal
                                </option>
                                <option value="monthly" {{ $config->frequency == 'monthly' ? 'selected' : '' }}>Mensual
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="time">Hora de ejecución</label>
                            <input type="time" name="time" id="time" class="form-control"
                                value="{{ $config->time }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Guardar Configuración
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Backups Existentes</span>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('backups.report.html') }}" class="btn btn-outline-info btn-sm" title="Exportar a HTML">
                        <i class="fas fa-file-code"></i>
                    </a>
                    <a href="{{ route('backups.report.pdf') }}" class="btn btn-outline-danger btn-sm" title="Exportar a PDF">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                    <a href="{{ route('backups.report.csv') }}" class="btn btn-outline-success btn-sm" title="Exportar a CSV">
                        <i class="fas fa-file-csv"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($backups->isEmpty())
                    <p>No hay backups disponibles</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Tamaño</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($backups as $backup)
                                    <tr>
                                        <td>{{ $backup['name'] }}</td>
                                        <td>{{ $backup['type'] }}</td>
                                        <td>{{ round($backup['size'] / 1024, 2) }} KB</td>
                                        <td>{{ $backup['date']->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('backups.download', $backup['name']) }}"
                                                class="btn btn-sm btn-success" title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        // Agregar esto al final de tu vista o en un archivo JS separado
        document.addEventListener('DOMContentLoaded', function() {
            // Para mostrar el modal de carga cuando se inicia un backup instantáneo
            const backupForm = document.getElementById('backupForm');
            if (backupForm) {
                backupForm.addEventListener('submit', function() {
                    $('#loadingModal').modal('show');
                });
            }

            // Auto-ocultar las alertas después de 5 segundos
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    $(alert).alert('close');
                }, 5000);
            });

            // Habilitar/deshabilitar los campos de configuración según el estado del checkbox
            const enabledCheckbox = document.getElementById('enabled');
            const frequencySelect = document.getElementById('frequency');
            const timeInput = document.getElementById('time');

            function toggleConfigFields() {
                const isEnabled = enabledCheckbox.checked;
                frequencySelect.disabled = !isEnabled;
                timeInput.disabled = !isEnabled;

                if (!isEnabled) {
                    frequencySelect.parentElement.classList.add('text-muted');
                    timeInput.parentElement.classList.add('text-muted');
                } else {
                    frequencySelect.parentElement.classList.remove('text-muted');
                    timeInput.parentElement.classList.remove('text-muted');
                }
            }

            if (enabledCheckbox) {
                // Ejecutar al cargar la página
                toggleConfigFields();

                // Ejecutar cuando cambie el estado del checkbox
                enabledCheckbox.addEventListener('change', toggleConfigFields);
            }

            // Aplicar tooltip a los botones de exportación
            $('[title]').tooltip();
        });
    </script>
@stop
