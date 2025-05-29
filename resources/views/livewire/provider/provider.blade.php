<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Escriba un nombre ..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Crear Proveedor</button>
        </div>

            @if($modal)
                @include('livewire.provider.provider-modal')
            @endif

            @if ( $providers && $providers->count() > 0)
              <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Productos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($providers as $provider)
                            <tr class="text-center">
                                <td class="align-middle">{{ $provider->name }}</td>
                                <td class="align-middle">{{ $provider->address }}</td>
                                <td class="align-middle">{{ $provider->phone }}</td>
                                <td class="align-middle">{{ $provider->email }}</td>
                                <td class="align-middle">
                                    @if ($provider->products->count() > 0)
                                        @foreach ($provider->products as $product)
                                            <span class="badge badge-primary">{{ $product->name }}</span>
                                        @endforeach
                                    @else
                                        Ninguno
                                    @endif                                          
                                <td class="align-middle">
                                    <button wire:click="edit({{ $provider->id }})" class="btn btn-sm btn-info">Editar</button>
                                    <button wire:click="$emit('confirmDelete', {{ $provider->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div class="card-footer">
                {{ $providers->onEachSide(1)->links() }}
            </div>
            @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>      
            @endif
          
    </div>
</div>
