@extends('adminlte::page')

@section('title', 'Egresos')

@section('content_header')
    <h1>Pagar pedidos</h1>
@stop

@section('content')
    @livewire('expenses')
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('orderPaymentCreated', () => {
        Swal.fire('¡Éxito!', 'Pago registrado correctamente.', 'success');
    });

    Livewire.on('orderPaymentUpdated', () => {
        Swal.fire('¡Éxito!', 'Egreso actualizado correctamente.', 'success');
    });

    Livewire.on('ExpenseDeleted', () => {
        Swal.fire('¡Eliminado!', 'Egreso eliminado correctamente.', 'success');
    });

    Livewire.on('ExpenseDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
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

    
