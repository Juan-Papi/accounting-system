<div>
    <div class="card shadow-sm border-0">
        <div class="card-header text-white">
            <input wire:model.debounce.500ms="search" type="text" class="form-control" placeholder="Buscar por descripción o cuenta...">
        </div>

        <div class="card-body bg-white">
            @forelse ($entries as $entry)
                <div class="mb-4 p-4 border-start border-4 border-primary bg-light rounded shadow-sm">
                    <h6 class="fw-bold text-dark mb-2">
                        N° Asiento: <span class="text-primary">AS-{{ str_pad($entry->id, 4, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($entry->date)->format('Y') }}</span> |
                        Fecha: <span class="text-secondary">{{ $entry->date->format('d/m/Y') }}</span> |
                        Descripción: <span class="text-dark">{{ $entry->description }}</span>
                    </h6>

                    <table class="table table-sm table-hover table-striped table-bordered mb-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Cuenta</th>
                                <th>Descripción</th>
                                <th>Debe</th>
                                <th>Haber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entry->details as $detail)
                                <tr class="text-center align-middle">
                                    <td>{{ $detail->account->code }} - {{ $detail->account->name }}</td>
                                    <td>{{ $detail->description }}</td>
                                    <td>{{ number_format($detail->debit, 2) }}</td>
                                    <td>{{ number_format($detail->credit, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    <strong>No hay asientos contables registrados...</strong>
                </div>
            @endforelse
        </div>

        <div class="card-footer bg-light text-center">
            {{ $entries->onEachSide(1)->links() }}
        </div>
    </div>
</div>
