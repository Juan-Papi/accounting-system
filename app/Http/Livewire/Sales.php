<?php

namespace App\Http\Livewire;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\AccountingAccount;

class Sales extends Component
{
    use WithPagination;

    public $search = '';
    public $user_id, $total_amount = 0, $status, $payment_status, $sale_date;
    public $saleId;
    public $modal = false;

    public $name_customer, $phone_customer;
    public $products;
    public $product_id, $quantity = 1;
    public $selectedProducts = [];

    public function render(){
        $sales = Sale::with('user')->where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                return $query->where('total_amount', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('status', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('payment_status', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(6);

        $this->products = Product::all();
        return view('livewire.sales.sales', compact('sales'));
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal(){
        $this->modal = false;
    }

    public function resetInputFields(){
        $this->saleId = null;
        $this->sale_date = '';
        $this->status = '';
        $this->payment_status = '';
        $this->product_id = '';
        $this->quantity = 1;
        $this->selectedProducts = [];
        $this->total_amount = 0;
    }

    public function addProduct(){
        $product = Product::find($this->product_id);
        if (!$product || $this->quantity < 1) {
            $this->emit('error', 'Seleccione un producto válido y una cantidad mayor a 0');
            return;
        }

        foreach ($this->selectedProducts as $index => $item) {
            if ($item['product_id'] == $product->id) {
                $this->selectedProducts[$index]['quantity'] += $this->quantity;
                $this->selectedProducts[$index]['subtotal'] += $product->price * $this->quantity;
                $this->calculateTotal();
                $this->product_id = '';
                $this->quantity = 1;
                return;
            }
        }

        $this->selectedProducts[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $this->quantity,
            'subtotal' => $product->price * $this->quantity,
        ];

        $this->calculateTotal();
        $this->product_id = '';
        $this->quantity = 1;
    }

    public function removeProduct($index){
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
        $this->calculateTotal();
    }

    public function calculateTotal(){
        $this->total_amount = collect($this->selectedProducts)->sum('subtotal');
    }

    public function save(){
        $validatedData = Validator::make($this->modelData(), (new SaleRequest())->rules())->validate();
        if (empty($this->selectedProducts)) {
            $this->emit('error', 'Debe agregar al menos un producto');
            return;
        }

        try {
            if($this->saleId){
                $sale = Sale::find($this->saleId);
                if (!$sale) {
                    $this->emit('error', 'Venta no encontrada');
                    return;
                }
                $sale->update($validatedData);
                // SaleItem::where('sale_id', $this->saleId)->delete();
                $this->emit('SaleUpdated');  

            } else {
           
            DB::beginTransaction();
            $sale = Sale::create($validatedData);

            foreach ($this->selectedProducts as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['subtotal'],
                ]);
            }

            // Registrar asiento contable de la venta
            $this->registrarAsientoVenta($sale);
              DB::commit();

            $this->emit('SaleCreated');
        }
            $this->closeModal();
            $this->resetInputFields();

        } catch (\Exception $e) {
            Log::error('Error al crear la venta: ' . $e->getMessage());
            $this->emit('error', 'Ocurrió un error al guardar la venta');
        }
    }

    public function edit($id){
        $this->modal = true;
        $sale = Sale::find($id);
        if (!$sale) {
            $this->emit('error', 'Venta no encontrada');
            return;
        }
        $this->saleId = $id;
        $this->sale_date = $sale->sale_date;
        $this->status = $sale->status;
        $this->payment_status = $sale->payment_status;
        $this->name_customer = $sale->name_customer;
        $this->phone_customer = $sale->phone_customer;

        foreach ($sale->saleItems as $item) {
            $this->selectedProducts[] = [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->total,
            ];
        }
    }

    public function delete($id){
        $sale = Sale::find($id);
        if (!$sale) {
            $this->emit('error', 'Venta no encontrada');
            return;
        }
        try {
            $sale->delete();
            $this->emit('SaleDeleted');  
        } catch (\Exception $e) {
            Log::error('Error al eliminar la venta: ' . $e->getMessage());
            $this->emit('error', 'Error al eliminar la venta');
        }
    }

    public function modelData(){
        return [
            'user_id' => Auth::id(),
            'sale_date' => $this->sale_date,
            'status' => $this->status,
            'payment_status' =>'pendiente',
            'total_amount' => $this->total_amount,
            'name_customer' => $this->name_customer,
            'phone_customer' => $this->phone_customer,
        ];
    }

    private function registrarAsientoVenta(Sale $sale) {
        // Busca las cuentas contables necesarias (ajusta los códigos a tu plan contable)
        $cuentaCxC = AccountingAccount::where('code', '1.1.03')->first(); // Cuentas por cobrar
        $cuentaVentas = AccountingAccount::where('code', '4.1.01')->first(); // Cuenta de ventas (ingresos)

        if (!$cuentaCxC || !$cuentaVentas) {
            throw new \Exception('No se encontraron las cuentas contables requeridas para el asiento de venta.');
        }

        // Crear asiento contable
        $journal = JournalEntry::create([
            'date' => now(),
            'description' => 'Registro de venta #' . $sale->id,
            'reference' => $sale->id,
            'user_id' => Auth::id(),
        ]);

        // Débito: cuentas por cobrar (cliente debe pagar)
        JournalEntryDetail::create([
            'journal_entry_id' => $journal->id,
            'accounting_account_id' => $cuentaCxC->id,
            'debit' => $sale->total_amount,
            'credit' => 0,
            'description' => 'Cliente debe pagar',
        ]);

        // Crédito: ventas (ingresos)
        JournalEntryDetail::create([
            'journal_entry_id' => $journal->id,
            'accounting_account_id' => $cuentaVentas->id,
            'debit' => 0,
            'credit' => $sale->total_amount,
            'description' => 'Ingreso por venta',
        ]);
    }
}
