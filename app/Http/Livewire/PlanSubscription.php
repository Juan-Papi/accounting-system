<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SubscriptionController;

use App\Models\Plan;

use Livewire\Component;

class PlanSubscription extends Component
{   
    public $planSubscriptionId;
    public $selectedPlan;
    public $qrImageBase64 = null;
    public $loadingQr = true;
    public $modal = false;

    public function render()
    {    $activeSubscription = Auth::user()->planSubscriptions()
        ->where('status', 'activo')
        ->where('end_time', '>', now())
        ->latest('end_time')
        ->first();

        $plans = Plan::all();
        return view('livewire.subscription.plan-subscription', ['activeSubscription' => $activeSubscription,'plans' => $plans]);
    }

    public function openModal($planId){
        $this->selectedPlan = Plan::find($planId);
        $this->modal = true;
        $this->generateQrCode();

    }

    public function closeModal(){
        $this->modal = false;
    }

    public function generateQrCode(){
        $this->qrImageBase64 = null;
        try {
            if ($this->selectedPlan) {
                $qr = SubscriptionController::obtenerQr($this->selectedPlan);
                if ($qr) {
                    $this->qrImageBase64  = $qr;
                } else {
                    $this->emit('error', $qr['Mensaje']);
                    // $this->emit('errorQr');  // Emitir el evento
                }
            }else{
                \Log::info('no se ha seleccionado un plan');

            }
            

        } catch (\Exception $e) {
            \Log::error('Error en generar qr: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            $this->emit('error', $e->getMessage());
        }
     
    }
}
