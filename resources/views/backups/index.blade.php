@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Backup - Copia de seguridad</h1> --}}
    <br>
@stop

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Gestión de Backups</h1>
            </div>
            <div class="col-md-4 text-right">
                <form action="{{ route('backups.create-instant') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Backup Instantáneo
                    </button>
                </form>
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
            <div class="card-header">Backups Existentes</div>
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
                                            <form action="{{ route('backups.destroy', $backup['name']) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
        console.log('Hi!');
        document.addEventListener('DOMContentLoaded', function() {
            const enabledCheckbox = document.getElementById('enabled');
            const frequencySelect = document.getElementById('frequency');
            const timeInput = document.getElementById('time');

            function toggleFields() {
                const isEnabled = enabledCheckbox.checked;
                frequencySelect.disabled = !isEnabled;
                timeInput.disabled = !isEnabled;
            }

            enabledCheckbox.addEventListener('change', toggleFields);
            toggleFields(); // Ejecutar al cargar la página
        });
    </script>
@stop
