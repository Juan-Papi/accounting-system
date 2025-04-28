<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <form wire:submit.prevent="save" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $productId ? 'Editar Producto' : 'Crear Producto' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Nombre -->
                    <div class="mb-2">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" wire:model="name" class="form-control" placeholder="Ingrese el nombre del producto">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-2">
                        <label for="description">Descripción</label>
                        <input id="description" wire:model="description" class="form-control" rows="4" placeholder="Ingrese una descripción del producto">
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!--precio y proveedor-->
                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="price">Precio</label>
                            <input id="price" type="number" step="0.01"  wire:model="price" class="form-control" placeholder="Ingrese el precio">
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stock">Stock</label>
                            <input id="stock" type="number" wire:model="stock" class="form-control" placeholder="Cantidad disponible">
                            @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <!-- Proveedor -->
                    <div class="mb-2">
                        <label for="provider_id">Proveedor</label>
                        <select id="provider_id" wire:model="provider_id" class="form-control">
                            <option value="">Seleccione proveedor</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach

                        </select>
                        @error('provider_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label for="img_url">Imagen</label>
                        <input type="file"wire:model="img_url" id="img_url" class="form-control" accept="image/*">
                        <div wire:loading wire:target="img_url">
                            <span class="text-sm text-gray-500">Cargando imagen...</span>
                        </div>
                        @error('img_url') <span class="text-danger">{{ $message }}</span> @enderror
                   
                        <!-- Vista previa -->
                        @if ($img_url)
                        <div class="mt-2 text-center">
                            <p class="text-sm text-gray-600">Vista previa:</p>
                            <div class="flex justify-center items-center">
                                <img src="{{ $img_url->temporaryUrl() }}" class="img-fluid rounded shadow" style="max-height: 200px;" alt="Vista previa de imagen">
                            </div>
                        </div>
                        @endif
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
