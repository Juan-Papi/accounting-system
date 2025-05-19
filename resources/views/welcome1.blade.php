@extends('layouts.app1')

@section('title', 'Landing Page')

@section('content')


    @livewire('plan-subscription')
  
@stop

@section('js')
{{-- ver donde colocarlo de manera global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
//     Livewire.on('errorQr', () => {
//         Swal.fire('Error', 'Error al generar Qr. Inténtelo más tarde', 'error');
//     });


//     Livewire.on('paymentVerified', () => {
//         Swal.fire({
//             icon: 'success',
//             title: '¡Suscripción confirmada!',
//             text: 'Tu pago fue validado exitosamente.',
//         });
//     });

//     Livewire.on('paymentFailed', (message) => {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error en la suscripción',
//             text: message,
//         });
//     });
//     Livewire.on('paymentPending', (message) => {
//         Swal.fire({
//             icon: 'warning',
//             title: 'Pago no verificado',
//             text: 'Pago pendiente. Por favor, realiza el pago.',
//         });
//     });

//    Livewire.on('loading', () => {
//         console.log("Evento 'loading' recibido");
//         document.getElementById('loadingOverlay').style.display = 'flex';
//         setTimeout(() => {
//             document.getElementById('loadingOverlay').style.display = 'none';
//             document.getElementById('qrImageContainer').style.display = 'block';
            
//         }, 5000); 
//     });

    
//    Livewire.on('loadingVerifyPay', () => {
//         console.log("Evento 'loadingVerifyPay' recibido");
//         const element = document.getElementById('loadingVerifyPay');
//         if (element) {
//             element.style.display = 'flex';
//         } else {
//             console.error('Elemento no encontrado');
//         }
//         setTimeout(() => {
//             element.style.display = 'none';
//         }, 7000); 
//     });



//     Livewire.on('error', function (error) {
//         console.error(error);
//     });
</script>

@stop
