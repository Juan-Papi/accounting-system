@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    @if (Auth::user()->roles->count() > 0)
        <p>Roles asignados para {{Auth::user()->name}} :</p>
        <ul>
            @foreach (Auth::user()->roles as $role)
                <li>{{ $role->name }}</li>
            @endforeach
        </ul>
    @else
        <p>No se han asignado roles al usuario.</p>
    @endif

    @livewire('plan-subscription')
  
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    Livewire.on('errorQr', () => {
        Swal.fire('Error', 'Error al generar Qr. Inténtelo más tarde', 'error');
    });

    Livewire.on('productUpdated', () => {
        Swal.fire('¡Éxito!', 'Producto actualizado correctamente.', 'success');
    });

    Livewire.on('productDeleted', () => {
        Swal.fire('¡Eliminado!', 'Producto eliminado correctamente.', 'success');
    });

    Livewire.on('productDeleteError', () => {
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
