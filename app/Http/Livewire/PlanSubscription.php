<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SubscriptionController;

use App\Models\Plan;
use App\Models\PlanSubscription as ModelPlanSubscription;
use App\Models\Qr;
use App\Models\User;

use Livewire\Component;

class PlanSubscription extends Component
{   
    public $planSubscription = null;
    public $selectedPlan;
    public $qrImageBase64 = null;
    public $loadingQr = true;
    public $modal = false;
    public $modalSuscription = false;
    public $motionId = null;
    public $buttonDisabled = false;
    public $buttonDisabledSubscribe = false;

    protected $listeners = [
        'reenableButton' => 'reenableButton',
        'refreshComponent' => '$refresh'
    ];

    public function render(){
        $activeSubscription = ModelPlanSubscription::where('user_id', Auth::user()->id)
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->first();
        $plans = Plan::all();
        return view('livewire.subscription.plan-subscription', [
            'activeSubscription' => $activeSubscription,
            'plans' => $plans,
        ]);
    }

    public function openModal($planId){
        $this->buttonDisabledSubscribe = false;
        $this->emit('loading');
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
                $planId = $this->selectedPlan->id;
                $price = $this->selectedPlan->price;
                
                $qrResponse = SubscriptionController::generarQr($this->selectedPlan, $this->selectedPlan->name);
    
                if ($qrResponse && isset($qrResponse['Codigo']) && $qrResponse['Codigo'] === 0) {
                    $base64 = $qrResponse['Data']['qr'] ?? null;
                    $this->motionId = $qrResponse['Data']['movimiento_id'] ?? 0;
    
                    if ($base64) {
                        $this->qrImageBase64 = 'data:image/png;base64,' . $base64;
                        Qr::updateOrCreate(
                            ['plan_id' => $planId],
                            [
                                'price' => $price, 
                                'qr_base64' => $base64, 
                                'status' => true,
                                'motion_id' => $this->motionId
                            ]
                        );
                    } else {
                        $this->emit('error', 'QR vacío en la respuesta del servidor.');
                    }
                } else {
                    $mensaje = $qrResponse['Mensaje'] ?? 'Error desconocido al generar el QR.';
                    $this->emit('error', $mensaje);
                }
    
            } else {
                Log::info('No se ha seleccionado un plan');
                $this->emit('error', 'Debe seleccionar un plan primero.');
            }
    
        } catch (\Exception $e) {
            Log::error('Error en generar qr: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            $this->emit('error', 'Ocurrió un error inesperado al generar el QR.');
        }
    }
    
    public function verifyQrPayment($moveId, $planId){
    
        $response = SubscriptionController::verifyAndRegisterSubscription($moveId, $planId);
        $result = is_array($response) ? $response : ($response ? $response->getData(true) : []);
    
        $this->buttonDisabled = true; 
    
        if (isset($result['success']) && $result['success'] === true) {
            $this->emit('paymentVerified');
            $this->planSubscription = $result['planSubscription'] ?? null;
    
        } else {
            if (isset($result['status']) && $result['status'] === 'pending') {
                $this->emit('paymentPending');
            } else {
                $this->emit('paymentFailed', $result['error'] ?? 'Error desconocido');
            }
        }
        $this->closeModal();

    }
       
}
