<div>
    <div class="card shadow-sm border-0">
        <div class="card-header  text-white">
            <input wire:model.debounce.500ms="search" type="text" class="form-control" placeholder="Buscar por cuenta o código...">
        </div>

        <div class="card-body bg-white">
            @forelse ($accounts as $account)
                <div class="mb-5 p-4 border-start border-4 border-success bg-light rounded shadow-sm">
                    <h6 class="fw-bold text-dark mb-3">
                        Cuenta: <span class="text-success">{{ $account->code }} - {{ $account->name }}</span>
                    </h6>

                    @if ($account->ledger->isNotEmpty())
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="table-success text-center">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Debe</th>
                                    <th>Haber</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($account->ledger as $detail)
                                    <tr class="text-center align-middle">
                                        <td>{{ $detail->journalEntry->date->format('d/m/Y') }}</td>
                                        <td>{{ $detail->description ?? $detail->journalEntry->description }}</td>
                                        <td>{{ number_format($detail->debit, 2) }}</td>
                                        <td>{{ number_format($detail->credit, 2) }}</td>
                                        <td>{{ number_format($detail->balance, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <strong>No hay movimientos para esta cuenta.</strong>
                        </div>
                    @endif
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    <strong>No hay cuentas contables encontradas.</strong>
                </div>
            @endforelse
        </div>

        <div class="card-footer bg-light text-center">
{{ $accounts->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
