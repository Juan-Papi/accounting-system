<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form wire:submit.prevent="save" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $providerId ? 'Editar Proveedor' : 'Crear Proveedor' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Nombre -->
                    <div class="mb-2">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" wire:model="name" class="form-control" placeholder="Ingrese el nombre del proveedor">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-2">
                        <label for="address">Dirección</label>
                        <input id="address" wire:model="address" class="form-control" rows="4" placeholder="Ingrese una dirección del proveedor">
                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!--precio y proveedor-->
                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="phone">Teléfono</label>
                            <input id="phone" type="text"  wire:model="phone" class="form-control" placeholder="Ingrese el teléfono del proveedor">
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input id="email" type="text" wire:model="email" class="form-control" placeholder="Ingrese el email del proveedor">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
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
