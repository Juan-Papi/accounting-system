@extends('adminlte::page')

@section('title', 'Cuentas')

@section('content_header')
    <h1>Cuentas</h1>
@stop

@section('content')
    @livewire('accouting-account')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('AccountCreated', () => {
        Swal.fire('¡Éxito!', 'Cuenta creada correctamente.', 'success');
    });

    Livewire.on('AccountUpdated', () => {
        Swal.fire('¡Éxito!', 'Cuenta actualizada correctamente.', 'success');
    });

    Livewire.on('AccountDeleted', () => {
        Swal.fire('¡Eliminado!', 'Cuenta eliminada correctamente.', 'success');
    });

    Livewire.on('AccountDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar la Cuenta.', 'error');
    });

    Livewire.on('confirmDelete', productId => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('delete', productId);
            }
        });
    });
    Livewire.on('error', function (error) {
        console.error("Error recibido: ",error);
    });
</script>

@stop

    
