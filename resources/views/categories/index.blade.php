@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <h1>Categorías</h1>
@stop

@section('content')
    @livewire('categories')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('categoryCreated', () => {
        Swal.fire('¡Éxito!', 'Categoría creada correctamente.', 'success');
    });

    Livewire.on('categoryUpdated', () => {
        Swal.fire('¡Éxito!', 'Categoría actualizada correctamente.', 'success');
    });

    Livewire.on('categoryDeleted', () => {
        Swal.fire('¡Eliminado!', 'Categoría eliminada correctamente.', 'success');
    });

    Livewire.on('categoryDeleteError', () => {
        Swal.fire('Error', 'No se pudo eliminar la categoría.', 'error');
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

    
