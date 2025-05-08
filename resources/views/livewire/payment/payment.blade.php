<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Buscar pago..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Registrar pago</button>
        </div>

        @if($modal)
            @include('livewire.payment.payment-modal')
        @endif

        @if ($payments && $payments->count() > 0)
            <div class="card-body">
                <h3>Pagos realizados</h3>
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Productos</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr class="text-center">
                                <td class="align-middle">{{ $payment->id }}</td>
                                <td class="align-middle">{{ $payment->sale->name_customer ?? 'Sin cliente' }}</td>
                                <td class="align-middle">{{ $payment->amount }}</td>
                                <td class="align-middle">{{ $payment->payment_date }}</td>
                                <td class="align-middle">
                                    @foreach ( $payment->sale->saleItems as $item)
                                    <span class="badge badge-info">{{$item->product->name}} </span>                                        
                                    @endforeach
                                </td>
                                <td class="align-middle">{{ $payment->method }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $payments->onEachSide(1)->links() }}
            </div>
        @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>
        @endif
    </div>
</div>
