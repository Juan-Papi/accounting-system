<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Escriba un nombre ..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Crear Venta</button>
        </div>

            @if($modal)
                @include('livewire.sales.sales-modal')
            @endif

            @if ( $sales && $sales->count() > 0)
              <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Productos</th>
                            <th>Total monto</th>
                            <th>Fecha</th>
                            <th>Estado de venta</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            {{-- <th>Productos</th> --}}
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr class="text-center">
                                <td class="align-middle">{{ $sale->id }}</td>
                                <td class="align-middle">
                                    @foreach ($sale->saleItems as $item)
                                    <span class="badge badge-info">{{$item->product->name}} </span>                                        
                                    @endforeach

                                </td>
                                <td class="align-middle">${{ $sale->total_amount }}</td>
                                <td class="align-middle">{{ $sale->sale_date }}</td>
                                <td class="align-middle">
                                    @if ($sale->status == 'pendiente')
                                        <span class="badge badge-warning">pendiente</span>                                        
                                    @else
                                        @if ($sale->status == 'completada')
                                            <span class="badge badge-success">completada</span>
                                        @else 
                                            @if($sale->status == 'cancelada')
                                                <span class="badge badge-danger">cancelada</span>
                                            @endif
                                        @endif
                                    @endif                                
                                    {{-- @if ($sale->payment_status == 'pendiente')
                                        <span class="badge badge-danger">pendiente</span>                                        
                                    @else
                                        @if ($sale->payment_status == 'parcial')
                                            <span class="badge badge-warning">en deuda</span>
                                        @else 
                                            @if($sale->payment_status == 'pagado')
                                                <span class="badge badge-success">pagado</span>
                                            @endif
                                        @endif
                                    @endif                                 --}}
                                </td>
                                <td class="align-middle">{{ $sale->name_customer ?? 'Sin registro' }}</td>                                                                       
                                <td class="align-middle">{{ $sale->phone_customer ?? 'Sin registro' }}</td>                                                                       
                                <td class="align-middle">
                                    <button wire:click="edit({{ $sale->id }})" class="btn btn-sm btn-info">Editar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div class="card-footer">
                {{ $sales->onEachSide(1)->links() }}
            </div>
            @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>      
            @endif
          
    </div>
</div>
