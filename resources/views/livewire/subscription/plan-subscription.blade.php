<div class="card">
    
  {{-- modal para mostrar qr del plan seleccionado --}}
  @if($modal)
  <div class="card-header">
      @include('livewire.subscription.plan-subscription-modal')
  </div>
  @endif

  <div class="card-body">
      @if ($activeSubscription == null)
      <div class="container">
        <h2 class="text-center mb-4">Suscríbete a un Plan</h2>
        <div class="row justify-content-center">
            @if ($plans->count() > 0)
                @foreach ($plans as $plan)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-top border-primary border-4 h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fs-4 fw-semibold text-center">Plan <strong>{{$plan->name}}</strong> </h5><br>
                            <p class="text-muted text-center">Ideal para usuarios individuales.</p>
                            <p class="display-6 text-center">Bs. {{$plan->price}}<span class="fs-6 text-muted">/mes</span></p>
                            <ul class="list-unstyled mb-4 text-center">
                                @foreach ($plan->detailPlans as $detail)
                                <li>✔️{{$detail->description }} </li>
                                @endforeach
                            </ul>
                            <button wire:click="openModal({{ $plan->id }})" class="btn btn-primary w-100"
                            @if($buttonDisabledSubscribe) disabled @endif
                            >Suscribirse</button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col">
                    <div class="alert alert-warning text-center" role="alert">
                        No hay planes disponibles en este momento.
                    </div>
                </div>
            @endif
        </div>
        
        @else
         
        <div>
          </div>
          <div id="subscriptionInfo" style="background-color: #f8f9fa; padding: 30px; border-radius: 12px; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); max-width: 700px; margin: 30px auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

            <!-- Título del div -->
            <h5 style="text-align: center; font-size: 1.6em; font-weight: 600; color: #343a40;">Detalles de la Suscripción</h5>
        
            <!-- Información del plan -->
            <div style="border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 20px;">
                <p style="font-size: 1.1em; color: #495057;"><strong>Plan: </strong><span id="planName" style="font-weight: 500; color: #007bff;">{{$activeSubscription->plan->name}}</span></p>
                <p style="font-size: 1.1em; color: #495057;"><strong>Precio: </strong><span id="planPrice" style="font-weight: 500; color: #28a745;">Bs. {{$activeSubscription->plan->price}}</span></p>
                <p style="font-size: 1.1em; color: #495057;"><strong>Fecha de Inicio: </strong><span id="planStartDate" style="font-weight: 500; color: #6c757d;">{{$activeSubscription->start_time}}</span></p>
                <p style="font-size: 1.1em; color: #495057;"><strong>Fecha de Fin: </strong><span id="planEndDate" style="font-weight: 500; color: #6c757d;">{{$activeSubscription->end_time}}</span></p>
            </div>
        </div>
      </div>
  @endif

  </div>
          {{-- spinner de carga para cargar imagen qr --}}
          <div id="loadingOverlay" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.8);
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Generando Qr...</p>
            </div>
        </div>

        {{-- spinner de carga para verificar pago --}}
        <div id="loadingVerifyPay" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.8);
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Comprobando pago....</p>
            </div>
        </div>

 </div>