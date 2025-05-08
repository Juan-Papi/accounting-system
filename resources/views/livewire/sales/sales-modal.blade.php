<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-dialog-scrollable" role="document">
        <form wire:submit.prevent="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $saleId ? 'Editar Venta' : 'Registrar Venta' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="sale_date">Fecha de Venta</label>
                            <input type="date" id="sale_date" wire:model="sale_date" class="form-control">
                            @error('sale_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status">Estado</label>
                            <select id="status" wire:model="status" class="form-control">
                                <option value="">Seleccione estado</option>
                                <option value="completada">Completado</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="name_customer">Cliente</label>
                            <input type="text" id="name_customer" wire:model="name_customer" class="form-control">
                            @error('name_customer') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone_customer">Teléfono</label>
                            <input type="text" id="phone_customer" wire:model="phone_customer" class="form-control">
                            @error('phone_customer') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-md-5">
                            <label>Producto</label>
                            <select wire:model="product_id" class="form-control">
                                <option value="">Seleccione producto</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} - Bs.{{ $product->price }}</option>
                                @endforeach
                            </select>
                            @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3">
                            <label>Cantidad</label>
                            <input type="number" wire:model="quantity" class="form-control" min="1" placeholder="Cantidad">
                            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" wire:click="addProduct" class="btn btn-primary">Agregar</button>
                        </div>
                    </div>

                    @if (!empty($selectedProducts))
                        <table class="table table-sm table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedProducts as $index => $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>Bs.{{ $item['price'] }}</td>
                                        <td>Bs.{{ number_format($item['subtotal'], 2) }}</td>
                                        <td>
                                            <button type="button" wire:click="removeProduct({{ $index }})" class="btn btn-sm btn-danger">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach

                          

                            </tbody>
                        </table>
                    @endif

                    <div class="text-end">
                        <strong>Total: Bs.{{ number_format($total_amount, 2) }}</strong>
                        @if (empty($selectedProducts))
                            <p class="text-danger">Agrega al menos un producto para realizar la venta</p>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button 
                    type="submit" 
                    class="btn btn-success" 
                    @disabled(empty($selectedProducts))
                    title="{{ empty($selectedProducts) ? 'Agrega al menos un producto para guardar la venta' : '' }}"
                    >Guardar Venta</button>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
