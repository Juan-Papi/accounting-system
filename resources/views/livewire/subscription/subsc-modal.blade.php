<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg max-w-lg w-full">
        <!-- Modal Header -->
        <div class="bg-blue-600 text-white rounded-t-lg p-4 flex justify-between items-center">
            <h5 class="text-lg font-bold">Confirmación de Suscripción</h5>
            <button type="button" class="text-white hover:text-gray-200" aria-label="Close" wire:click="closeModal">
                ✖
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-4">
                <h5 class="font-bold">Plan seleccionado: <span class="text-blue-600">{{ $selectedPlan->name }}</span></h5>
                <p class="mb-1">Precio: <strong>Bs. {{ number_format($selectedPlan->price, 2) }}</strong></p>
                <p>Duración: <strong>{{ $selectedPlan->duration_days }} días</strong></p>
            </div>

            <div class="text-center">
                <h6 class="font-semibold mb-3">Escanea el siguiente código QR para realizar el pago:</h6>

                <!-- Spinner mientras se genera el QR -->
                <div wire:loading wire:target="generateQrCode">
                    <div class="animate-spin h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full mx-auto"></div>
                    <p class="mt-2 text-blue-700 font-semibold">Generando código QR...</p>
                </div>

                <!-- Mostrar imagen del QR cuando ya esté lista -->
                <div wire:loading.remove wire:target="generateQrCode">
                    @if($qrImageBase64)
                        <div class="text-center my-3 hidden" id="qrImageContainer">
                            {{-- <img src="{{ $qrImageBase64 }}" class="rounded shadow-md max-w-xs mx-auto" alt="QR generado"> --}}
                            <img src="{{ $qrImageBase64 }}" class="rounded shadow-md max-w-[250px] mx-auto" style="max-width: 250px;" alt="QR generado">
                        </div>
                    @endif
                </div><br>
                <h6 class="font-semibold mb-3">Una vez realizado el pago, presiona confirmar subscripción</h6>


                <!-- Spinner verificando pago -->
                <div wire:loading wire:target="verifyQrPayment">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin h-10 w-10 border-4 border-blue-500 border-t-transparent rounded-full"></div>
                        <span class="ml-3 text-blue-700 font-semibold">Verificando pago...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-between p-4 bg-gray-100 rounded-b-lg">
            <button type="button" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition" wire:click="closeModal">
                Cancelar
            </button>

            @if($qrImageBase64)
                <button wire:click="verifyQrPayment('{{ $motionId }}', '{{ $selectedPlan->id }}')"
                        class=" text-white py-2 px-4 rounded-md hover:bg-green-600 transition"
                        style="background: green">
                    Confirmar Suscripción
                </button>
            @endif
        </div>
    </div>
</div>