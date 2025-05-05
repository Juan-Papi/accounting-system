<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Confirmación de Suscripción</h5>
                <button type="button" class="btn-close btn-close-white" aria-label="Close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h5 class="fw-bold">Plan seleccionado: <span class="text-primary">{{ $selectedPlan->name }}</span></h5>
                    <p class="mb-1">Precio: <strong>Bs. {{ number_format($selectedPlan->price, 2) }}</strong></p>
                    <p>Duración: <strong>{{ $selectedPlan->duration_days }} días</strong></p>
                </div>

                <div class="text-center">
                    <h6 class="fw-semibold mb-3">Escanea el siguiente código QR para realizar el pago:</h6>
                    {{-- <img src="{{ asset('images/qr.png') }}" alt="QR de pago" class="img-fluid rounded shadow-sm" style="max-width: 250px;"> --}}

                    {{-- Spinner mientras se genera el QR --}}
      
                    <div wire:loading wire:target="generateQrCode">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Generando código QR...</p>
                    </div>
    
                    {{-- Mostrar imagen del QR cuando ya esté lista --}}
                    @if($qrImageBase64)
                    <div class="text-center my-3">
                        <img src="{{$qrImageBase64}}" class="img-fluid" alt="QR generado">
                    </div>
                    @endif
    
    
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-between px-4 pb-4">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-success">
                    Confirmar Suscripción
                </button>
            </div>
        </div>
    </div>
</div>
