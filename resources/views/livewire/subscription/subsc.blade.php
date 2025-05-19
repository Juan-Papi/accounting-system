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
                                            class="mt-4 w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
                                            @if($buttonDisabledSubscribe) disabled @endif>
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
    <div id="loadingOverlay" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center hidden">
        <div class="text-center">
            <div class="animate-spin h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full"></div>
            <p class="mt-2 text-blue-700 font-semibold">Generando Qr...</p>
        </div>
    </div>

    {{-- Spinner de carga para verificar pago --}}
    <div id="loadingVerifyPay" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center hidden">
        <div class="text-center">
            <div class="animate-spin h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full"></div>
            <p class="mt-2 text-blue-700 font-semibold">Comprobando pago...</p>
        </div>
    </div>

</div>
