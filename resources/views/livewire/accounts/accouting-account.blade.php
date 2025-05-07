<div>
    <div class="card">
        <div class="card-header">
            <input wire:model.debounce.500ms="search" class="form-control w-100" placeholder="Buscar por nombre o código..." type="text">
        </div>
        <div class="card-header">
            <button wire:click="openModal" class="btn btn-primary mb-3">Crear Cuenta Contable</button>
        </div>

        @if($modal)
            @include('livewire.accounts.accouting-account-modal')
        @endif

        @if ($accounts && $accounts->count() > 0)
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>¿Es Padre?</th>
                            <th>Cuenta Padre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                            <tr class="text-center">
                                <td class="align-middle">{{ $account->code }}</td>
                                <td class="align-middle">{{ $account->name }}</td>
                                <td class="align-middle">
                                    @switch($account->type)
                                        @case('activo') Activo @break
                                        @case('pasivo') Pasivo @break
                                        @case('patrimonio') Patrimonio @break
                                        @case('ingreso') Ingreso @break
                                        @case('gasto') Gasto @break
                                    @endswitch
                                </td>
                                <td class="align-middle">
                                    {{ $account->is_parent ? 'Sí' : 'No' }}
                                </td>
                                <td class="align-middle">
                                    {{ $account->parent?->name ?? '-' }}
                                </td>
                                <td class="align-middle">
                                    <button wire:click="edit({{ $account->id }})" class="btn btn-sm btn-info">Editar</button>
                                    <button wire:click="$emit('confirmDelete', {{ $account->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $accounts->onEachSide(1)->links() }}
            </div>
        @else
            <div class="card-body">
                <strong>No hay cuentas contables registradas...</strong>
            </div>
        @endif
    </div>
</div>
