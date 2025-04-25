<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaymentMethod extends Component
{
    //empieza en get y termina en property por tanto es una propiedad computada
    public function getDefaultPaymentMethodProperty()
    {
        return auth()->user()->defaultPaymentMethod();
    }

    public function addPaymentMethod($paymentMethod)
    {
        auth()->user()->addPaymentMethod($paymentMethod);
        if (!auth()->user()->hasDefaultPaymentMethod()) {
            auth()->user()->updateDefaultPaymentMethod($paymentMethod);
        }
    }

    public function deletePaymentMethod($paymentMethod)
    {
        // dd($paymentMethod);//Para verificar si se esta enviando la informacion
        auth()->user()->deletePaymentMethod($paymentMethod);
    }

    public function defaultPaymentMethod($paymentMethod)
    {
        //dd($paymentMethod);//Para verificar si se esta enviando la informacion
        auth()->user()->updateDefaultPaymentMethod($paymentMethod);
    }

    public function render()
    {
        return view('livewire.payment-method', [
            'intent' => auth()->user()->createSetupIntent(),
            'paymentMethods' => auth()->user()->paymentMethods(),
        ]);
    }
}
