<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form wire:submit.prevent="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar pago</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="sale_id">Venta</label>
                        <select id="sale_id" wire:model="sale_id" class="form-control">
                            <option value="">Seleccione venta</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}">{{ $sale->name_customer }} - {{ $sale->total_amount }} Bs.</option>
                            @endforeach
                        </select>
                        @error('sale_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="customer_name">Cliente</label>
                            <input type="text" wire:model="customer_name" class="form-control" readonly>
                            @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_phone">Teléfono</label>
                            <input type="text" wire:model="customer_phone" class="form-control" readonly>
                            @error('customer_phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="payment_date">Fecha</label>
                            <input type="date" wire:model="payment_date" class="form-control" required>
                            @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="balance">Saldo</label>
                            <input type="number" step="0.01" wire:model="balance" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-6">
                            <label for="amount">Pago</label>
                            <input type="number" step="0.01" wire:model="amount" class="form-control" required placeholder="Monto">
                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="method">Método</label>
                            <select wire:model="method" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                            @error('method') <span class="text-danger">{{ $message }}</span> @enderror
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
