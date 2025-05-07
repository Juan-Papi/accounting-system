<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Escriba un nombre ..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Crear Pedido</button>
        </div>

            @if($modal)
                @include('livewire.orders.order-modal')
            @endif

            @if ( $orders && $orders->count() > 0)
              <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Proveedor</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Productos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="text-center">
                                <td class="align-middle">{{ $order->id }}</td>
                                <td class="align-middle">{{ $order->provider->name }}</td>
                                <td class="align-middle">{{ $order->status }}</td>
                                <td class="align-middle">{{ $order->created_at }}</td>
                                <td class="align-middle">{{ number_format($order->total_price, 2) }}</td>
                                <td class="align-middle">
                                    @foreach ($order->products as $product)
                                        {{ $product->name }},
                                    @endforeach
                                </td>
                                
                                <td class="align-middle">
                                    <button wire:click="edit({{ $order->id }})" class="btn btn-sm btn-info">Editar</button>
                                    <button wire:click="$emit('confirmDelete', {{ $order->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div class="card-footer">
                {{ $orders->onEachSide(1)->links() }}
            </div>
            @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>      
            @endif
          
    </div>
</div>
