<?php

namespace App\Http\Livewire;

use App\Http\Requests\OrderRequest;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Provider;

class Order extends Component
{   
    use WithPagination;
    public $quantity, $total_price, $status, $user_id, $provider_id, $product_id, $balance;
    public $orderId;
    public $modal = false;
    public $providers;
    public $products;
    public $search = '';
    protected $paginationTheme = "bootstrap";
    protected $listeners = ['delete' => 'delete'];
    public $purchase_price = 0;
    public $providerProducts = [];

    public function render(){    
        $orders = OrderModel::with('provider')->where('user_id', auth()->user()->id)
        ->when($this->search, function ($query) {
            return $query->where('quantity', 'LIKE', '%' . $this->search . '%')
                ->orWhere('total_price', 'LIKE', '%' . $this->search . '%')
                ->orWhere('status', 'LIKE', '%' . $this->search . '%');
        })
        ->orderBy('id', 'DESC')
        ->paginate(6);
        $this->providers = Provider::all();
        return view('livewire.orders.order', ['orders' => $orders] );   
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }
    
    public function closeModal(){
        $this->modal = false;
    }

    public function resetInputFields(){
        $this->quantity = '';
        $this->total_price = '';
        $this->status = 0;
        $this->user_id = '';
        $this->provider_id = '';
        $this->product_id = '';
        $this->orderId = null;
    }
    public function save(){

        $validatedData = Validator::make($this->modelData(), (new OrderRequest())->rules())->validate();

        try {
            if($this->orderId){
                $order = OrderModel::find($this->orderId);
                if (!$order) {
                    $this->emit('error', 'Pedido no encontrado');
                    return;
                }
                $order->update($validatedData);
                $this->emit('orderUpdated');  

            } else {

                $orden = OrderModel::create($validatedData);
                ProductOrder::create([
                    'order_id' => $orden->id,
                    'product_id' => $this->product_id,
                    'quantity' => $this->quantity,
                    'subtotal' => $this->total_price / $this->quantity,
                    'total_price' =>$this->total_price,	
                ]);

                $this->emit('orderCreated');  
            }
            $this->closeModal();
            $this->resetInputFields();
           
        } catch (\Exception $e) {
            Log::error('Error en crear pedido: ' . $e->getMessage());
            $this->emit('error', $e->getMessage());
        }
    }

    public function edit($id){
        $this->modal = true;
        $order = OrderModel::find($id);
        if (!$order) {
            $this->emit('error', 'Pedido no encontrado');
            return;
        }
        $this->quantity = $order->quantity;
        $this->total_price = $order->total_price;
        $this->status = $order->status;
        $this->user_id = $order->user_id;
        $this->provider_id = $order->provider_id;
        $this->orderId = $id;
    }

    public function delete($id){
        $order = OrderModel::find($id);
        if (!$order) {
            $this->emit('error', 'Pedido no encontrado');
            return;
        }
        try {
            $order->delete();
            $this->emit('orderDeleted');  // Emitir el evento
        } catch (\Exception $e) {
            Log::error('Error al eliminar el pedido: ' . $e->getMessage());
            $this->emit('error', 'Error al eliminar el pedido');
        }
    }

    private function modelData(){
        return [
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => 0,
            'user_id' => Auth::user()->id,
            'provider_id' => $this->provider_id,
            'balance'=> $this->total_price,
        ];
    }

    public function updatedProviderId($providerId){


        if ($providerId) {
            $this->providerProducts = Product::where('provider_id', $providerId)->get();
        } else {
            $this->providerProducts = [];
        }
    } 

    public function updatedProductId($productId){
        if ($productId) {
            $product = Product::find($productId);
            if ($product && $this->quantity) {
                $this->total_price = $this->quantity * $product->purchase_price;
            } else {
                $this->total_price = '';
            }
        } else {
            $this->total_price = '';
        }
    }

    public function updatedQuantity($qty){
        if ($this->product_id && $qty > 0) {
            $product = Product::find($this->product_id);
            if ($product) {
                $this->total_price = $qty * $product->purchase_price;
            }
        } else {
            $this->total_price = '';
        }
    }  
}
