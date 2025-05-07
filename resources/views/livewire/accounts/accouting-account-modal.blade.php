<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form wire:submit.prevent="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $accountId ? 'Editar Cuenta Contable' : 'Crear Cuenta Contable' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body">

                    <!-- Código -->
                    <div class="mb-2">
                        <label for="code">Código</label>
                        <input id="code" type="text" wire:model="code" class="form-control" placeholder="Ingrese el código">
                        @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="mb-2">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" wire:model="name" class="form-control" placeholder="Ingrese el nombre de la cuenta">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tipo -->
                    <div class="mb-2">
                        <label for="type">Tipo</label>
                        <select id="type" wire:model="type" class="form-control">
                            <option value="">Seleccione un tipo</option>
                            <option value="activo">Activo</option>
                            <option value="pasivo">Pasivo</option>
                            <option value="patrimonio">Patrimonio</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- ¿Es cuenta padre? -->
                    <div class="mb-2">
                        <label for="is_parent">¿Es cuenta padre?</label>
                        <select id="is_parent" wire:model="is_parent" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                        @error('is_parent') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Cuenta padre -->
                    <div class="mb-2">
                        <label for="parent_account_id">Cuenta Padre</label>
                        <select id="parent_account_id" wire:model="parent_account_id" class="form-control">
                            <option value="">Seleccione cuenta padre</option>
                            @foreach($parentAccounts as $account)
                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_account_id') <span class="text-danger">{{ $message }}</span> @enderror
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
