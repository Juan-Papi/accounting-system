<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Escriba un nombre ..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Crear Categoría</button>
        </div>

            @if($modal)
                @include('livewire.categories.category-modal')
            @endif

            @if ( $categories && $categories->count() > 0)
              <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="text-center">
                
                                <td class="align-middle">{{ $category->name }}</td>
                                <td class="align-middle">{{ $category->description }}</td>
                                <td class="align-middle">
                                    @if ($category->status == true)
                                        <span class="badge badge-success">Activa</span>                                        
                                    @else
                                    <span class="badge badge-warning">Inactiva</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <button wire:click="edit({{ $category->id }})" class="btn btn-sm btn-info">Editar</button>
                                    <button wire:click="$emit('confirmDelete', {{ $category->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div class="card-footer">
                {{ $categories->onEachSide(1)->links() }}
            </div>
            @else
            <div class="card-body">
                <strong>No hay registros ...</strong>
            </div>      
            @endif
          
    </div>
</div>
