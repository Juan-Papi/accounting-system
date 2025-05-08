@extends('adminlte::page')

@section('title', 'Pagos')

@section('content_header')
    <h1>Pagos</h1>
@stop

@section('content')
    @livewire('payment')
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('paymentCreated', () => {
        Swal.fire('¡Éxito!', 'Pago de la venta registrado correctamente.', 'success');
    });

    Livewire.on('PaymentUpdated', () => {
        Swal.fire('¡Éxito!', 'Pago de la venta actualizado correctamente.', 'success');
    });

    Livewire.on('PaymentDeleted', () => {
        Swal.fire('¡Eliminado!', 'Pago de la venta eliminado correctamente.', 'success');
    });

    Livewire.on('PaymentDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar el pago de la venta.', 'error');
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

    
