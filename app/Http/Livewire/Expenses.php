<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Http\Requests\OrderPayment as RequestsOrderPayment;

class Expenses extends Component
{   
    use WithPagination;
    public $amount, $note, $order_id, $payment_date, $payment_method;
    public $expenseId;
    public $modal = false;
    public $search = '';
    public $balance = 0;

    public function render()
    {   
        //pedidos sin pagar
        $orders = Order::where('user_id', auth()->user()->id)
        ->where('status', 0)
        ->get();

        //pedidos pagados          
          $paidOrders = OrderPayment::with('order')
            ->whereHas('order', function($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when($this->search, function ($query) {
                return $query->where('amount', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('note', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('payment_date', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(6);
        //dd($this->paidOrders);
            

        return view('livewire.expenses.expenses', compact('orders','paidOrders') );
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal(){
        $this->modal = false;
    }

    public function resetInputFields(){
        $this->amount = '';
        $this->note = '';
        $this->order_id = '';
        $this->payment_date = '';
        $this->payment_method = '';
        $this->expenseId = null;
    }

    public function save(){
        
        try {
            $validatedData = Validator::make($this->modelData(), (new RequestsOrderPayment())->rules())->validate();
            OrderPayment::create($validatedData);

            $order = Order::find($this->order_id);
            if (!$order) {
                $this->emit('error', 'Pedido no encontrado');
                return;
            }
            // actualiza el saldo del pedido
            $balance = $order->balance - $this->amount;
            $order->update([
                'status' => $balance <= 0 ? 1 : 0,
                'balance' => $balance,
            ]);

            $this->emit('orderPaymentCreated');
            
        } catch (\Exception $e) {
            Log::error('Error en crear el pago: ' . $e->getMessage());
            $this->emit('error', $e->getMessage());
        }
    }

    public function modelData(){
        return [
            'amount' => $this->amount,
            'note' => $this->note,
            'order_id' => $this->order_id,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
        ];
    }

    public function updatedOrderId($orderId){
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->emit('error', 'Pedido no encontrado');
                return;
            }
            $this->balance = $order->balance;
        } else {
            $this->balance = 0;
        }

    }
}
