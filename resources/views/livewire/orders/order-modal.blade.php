<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <form wire:submit.prevent="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $orderId ? 'Editar Pedido' : 'Crear Pedido' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="provider_id">Proveedor</label>
                            <select id="provider_id" wire:model="provider_id" class="form-control">
                                <option value="">Seleccione proveedor</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if (!empty($providerProducts))
                            <div class="col-md-6">
                                <label for="selected_product">Productos</label>
                                <select id="selected_product" wire:model="product_id" class="form-control">
                                    <option value="">Seleccione un producto</option>
                                    @foreach($providerProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} - Bs.{{ $product->purchase_price }}</option>
                                    @endforeach
                                </select>
                                @error('selectedProduct') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="col-md-6 mt-2">
                                <p class="text-danger">Seleccione un proveedor para elegir uno de sus productos</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="quantity">Cantidad</label>
                                <input id="quantity" type="number" wire:model="quantity" class="form-control" placeholder="Ingrese la cantidad">
                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="total_price">Precio pedido</label>
                                <input id="total_price" type="number" step="0.01" wire:model="total_price" class="form-control" placeholder="Precio total" readonly>
                                {{-- Este campo es readonly, se actualiza automáticamente --}}
                            </div>
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
