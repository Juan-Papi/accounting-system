
<div class=" bg-white shadow-lg rounded-lg ">

    {{-- Modal para mostrar QR del plan seleccionado --}}
    @if($modal)
        <div class="border-b pb-4">
            @include('livewire.subscription.subsc-modal')
        </div>
    @endif

    <div class="mt-4 ">
        @if ($activeSubscription == null)
            <div class="mx-auto">
                <h2 class="text-center text-2xl font-semibold text-gray-700 mt-3 mb-3">Suscríbete a un Plan</h2>
                <div class="flex flex-wrap justify-center gap-6">
                    @if ($plans->count() > 0)
                        @foreach ($plans as $plan)
                            <div class="w-full  p-4">
                                <div class="border-t-4 border-blue-500 rounded-lg shadow-md p-6">
                                    <h5 class="text-xl text-black font-bold text-center">Plan <strong>{{$plan->name}}</strong></h5>
                                    <p class="text-gray-600 text-center mt-2">Ideal para usuarios individuales.</p>
                                    <p class="text-3xl font-semibold text-center text-green-500">Bs. {{$plan->price}}<span class="text-sm text-gray-500">/mes</span></p>
                                    <ul class="mt-4 space-y-2 text-center">
                                        @foreach ($plan->detailPlans as $detail)
                                            <li class="text-green-500">✔️ {{$detail->description}}</li>
                                        @endforeach
                                    </ul>
                                    <button wire:click="openModal({{ $plan->id }})" 
                                            class="mt-4 w-full text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
                                            style="background: blue">
                                        Suscribirse
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full text-center">
                            <div class="bg-yellow-100 text-yellow-700 px-4 py-3 rounded-md">
                                No hay planes disponibles en este momento.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div id="subscriptionInfo" class="bg-gray-100 p-6 rounded-lg shadow-md max-w-lg mx-auto mt-6">
                <h5 class="text-center text-lg font-bold text-gray-800">Detalles de la Suscripción</h5>
                <div class="border-b-2 border-blue-500 pb-4 mt-4">
                    <p class="text-gray-700"><strong>Plan:</strong> <span class="font-semibold text-blue-500">{{$activeSubscription->plan->name}}</span></p>
                    <p class="text-gray-700"><strong>Precio:</strong> <span class="font-semibold text-green-500">Bs. {{$activeSubscription->plan->price}}</span></p>
                    <p class="text-gray-700"><strong>Fecha de Inicio:</strong> <span class="font-semibold text-gray-500">{{$activeSubscription->start_time}}</span></p>
                    <p class="text-gray-700"><strong>Fecha de Fin:</strong> <span class="font-semibold text-gray-500">{{$activeSubscription->end_time}}</span></p>
                </div>
            </div>
        @endif
    </div>

    {{-- Spinner de carga para generar QR --}}
  <div id="loadingOverlay" style="position: fixed; inset: 0; background-color: rgba(255, 255, 255, 0.8); display: none; align-items: center; justify-content: center;">
    <div style="text-align: center;">
        <div style="animation: spin 1s linear infinite; height: 3rem; width: 3rem; border-width: 4px; border-style: solid; border-color: #3b82f6 transparent transparent transparent; border-radius: 9999px;"></div>
        <p style="margin-top: 0.5rem; color: #1d4ed8; font-weight: 600;">Generando Qr...</p>
    </div>
</div>

    {{-- Spinner de carga para verificar pago --}}
   <div id="loadingVerifyPay" style="position: fixed; inset: 0; background-color: rgba(255, 255, 255, 0.8); display: none; align-items: center; justify-content: center;">
    <div style="text-align: center;">
        <div style="animation: spin 1s linear infinite; height: 3rem; width: 3rem; border-width: 4px; border-style: solid; border-color: #3b82f6 transparent transparent transparent; border-radius: 9999px;"></div>
        <p style="margin-top: 0.5rem; color: #1d4ed8; font-weight: 600;">Comprobando pago...</p>
    </div>
</div>

</div>
