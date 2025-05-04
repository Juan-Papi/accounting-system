<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.lazy="buscar" wire:keydown="limpiar_page" class="form-control w-100" placeholder="Escriba un nombre ..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Crear Producto</button>
        </div>

            @if($modal)
                @include('livewire.product.product-modal')
            @endif

            @if ( $products && $products->count() > 0)
              <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Proveedor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr class="text-center">
                                <td class="align-middle">
                                    @if ($product['img_url'] && $product['img_url'] != '')
                                        <img src="{{ asset('storage/' . $product['img_url']) }}" alt="Imagen de {{ $product['name'] }}" class="img-fluid" style="max-height: 50px; max-width: 50px;">
                                    @else
                                        Sin imagen
                                    @endif
                                </td>
                                <td class="align-middle">{{ $product->name }}</td>
                                <td class="align-middle">${{ $product->price }}</td>
                                <td class="align-middle">{{ $product->stock }}</td>
                                <td class="align-middle">{{ $product->provider->name }}</td>
                                <td class="align-middle">
                                    <button wire:click="edit({{ $product->id }})" class="btn btn-sm btn-info">Editar</button>
                                    <button wire:click="$emit('confirmDelete', {{ $product->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div class="card-footer">
                {{ $products->onEachSide(1)->links() }}
            </div>
            @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>      
            @endif
          
    </div>
</div>
