<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form wire:submit.prevent="save" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pagar pedido</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- pedido -->
                    <div class="mb-2">
                        <label for="name">Pedido</label>
                        <select id="order_id" wire:model="order_id" class="form-control">
                            <option value="">Seleccione pedido</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}">{{ $order->products->first()->name }} - {{ $order->quantity}} unidades </option>
                            @endforeach
                        </select>                        
                        @error('order_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="payment_date">Fecha de cobro</label>
                            <input type="date" wire:model="payment_date"  class="form-control" required>
                            @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="balance">Monto (Bs.)</label>
                            <input id="balance" type="number" step="0.01" wire:model="balance" class="form-control"  readonly>
                            @error('balance') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="amount">Pago</label>
                            <input id="amount" type="number" step="0.01" wire:model="amount" class="form-control" placeholder="Ingrese el monto a pagar" required>
                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="payment_method">Método de pago</label>
                            <select id="payment_method" wire:model="payment_method" class="form-control">
                                <option value="">Seleccione un método</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>    
                                {{-- <option value="Tarjeta">Tarjeta</option> --}}
                            </select> 
                            @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="name">Detalle</label>
                        <textarea id="note" wire:model="note" class="form-control" rows="4" placeholder="Ingrese una nota"></textarea>
                        @error('note') <span class="text-danger">{{ $message }}</span> @enderror                        
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
