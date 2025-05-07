@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>Pedidos</h1>
@stop

@section('content')
    @livewire('order')
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('orderCreated', () => {
        Swal.fire('¡Éxito!', 'Pedido creado correctamente.', 'success');
    });

    Livewire.on('ordertUpdated', () => {
        Swal.fire('¡Éxito!', 'Pedido actualizado correctamente.', 'success');
    });

    Livewire.on('orderDeleted', () => {
        Swal.fire('¡Eliminado!', 'Pedido eliminado correctamente.', 'success');
    });

    Livewire.on('orderDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar el Pedido.', 'error');
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

    
