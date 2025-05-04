@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <h1>Proveedores</h1>
@stop

@section('content')
    @livewire('provider')
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('providerCreated', () => {
        Swal.fire('¡Éxito!', 'Proveedor creado correctamente.', 'success');
    });

    Livewire.on('providerUpdated', () => {
        Swal.fire('¡Éxito!', 'Proveedor actualizado correctamente.', 'success');
    });

    Livewire.on('providerDeleted', () => {
        Swal.fire('¡Eliminado!', 'Proveedor eliminado correctamente.', 'success');
    });

    Livewire.on('providerDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar el proveedor.', 'error');
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
        console.error(error);
    });
</script>

@stop

    
