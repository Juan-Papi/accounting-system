@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <h1>Ventas</h1>
@stop

@section('content')
    @livewire('sales')
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('SaleCreated', () => {
        Swal.fire('¡Éxito!', 'Venta creada correctamente.', 'success');
    });

    Livewire.on('SaleUpdated', () => {
        Swal.fire('¡Éxito!', 'Venta actualizada correctamente.', 'success');
    });

    Livewire.on('SaleDeleted', () => {
        Swal.fire('¡Eliminado!', 'Venta eliminada correctamente.', 'success');
    });

    Livewire.on('SaleDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar la venta.', 'error');
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

    
