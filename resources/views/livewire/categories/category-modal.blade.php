<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form wire:submit.prevent="store" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $categoryId ? 'Editar Categoría' : 'Crear Categoría' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Nombre -->
                    <div class="mb-2">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" wire:model="name" class="form-control" placeholder="Ingrese el nombre de la categoría">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-2">
                        <label for="description">Descripción</label>
                        <input id="description" wire:model="description" class="form-control" rows="4" placeholder="Ingrese una descripción de la categoría">
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
