<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Escriba un nombre ..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Pagar pedido</button>
        </div>

            @if($modal)
                @include('livewire.expenses.expenses-modal')
            @endif

            @if ( $paidOrders && $paidOrders->count() > 0)
              <div class="card-body">
                <h3>Lista de pagos realizados</h3>
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Id</th>
                            <th>Pedido</th>
                            <th>Cantidad</th>
                            <th>Fecha de pago</th>
                            <th>Método de pago</th>
                            <th>Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paidOrders as $paidOrder)
                            <tr class="text-center">
                                <td class="align-middle">{{ $paidOrder->id }}</td>
                                <td class="align-middle">{{ optional($paidOrder->order->products->first())->name ?? 'Sin registro' }}</td>                            
                                <td class="align-middle">{{ $paidOrder->amount }}</td>
                                <td class="align-middle">{{ $paidOrder->payment_date }}</td>
                                <td class="align-middle">{{ $paidOrder->payment_method }}</td>
                                <td class="align-middle">{{ $paidOrder->note }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div class="card-footer">
                {{ $paidOrders->onEachSide(1)->links() }}
            </div>
            @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>      
            @endif
          
    </div>
</div>
