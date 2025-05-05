<div class="card">
  <div class="card-header">
      <h2 class="text-center fw-bold">Planes de Suscripción</h2>
  </div>
  @if($modal)
    @include('livewire.subscription.plan-subscription-modal')
  @endif
  <div class="card-body">
      @if ($activeSubscription == null)
      <div class="container">
      
          
          <div class="row row-cols-1 row-cols-md-3 g-4">
              @if ($plans->count() > 0)
                  @foreach ($plans as $plan)
                  <div class="col ">
                      <div class="card border-top border-primary border-4 h-100 shadow-sm">
                          <div class="card-body">
                              <h5 class="card-title fs-4 fw-semibold">Plan <strong>{{$plan->name}}</strong> </h5><br>
                              <p class="text-muted">Ideal para usuarios individuales.</p>
                              <p class="display-6">Bs. {{$plan->price}}<span class="fs-6 text-muted">/mes</span></p>
                              <ul class="list-unstyled mb-4">
                                  @foreach ($plan->detailPlans as $detail)
                                  <li>✔️{{$detail->description }} </li>
                                      
                                  @endforeach
                              </ul>
                              <button wire:click="openModal({{ $plan->id }})" class="btn btn-primary w-100">Suscribirse</button>
                            </div>
                      </div>
                  </div>
                  @endforeach
                  
              @else
              <div class="col">
                  <div class="alert alert-info">No hay planes disponibles en este momento.</div>
              </div>
              @endif
          </div>
      </div>
  @endif
  </div>
 </div>