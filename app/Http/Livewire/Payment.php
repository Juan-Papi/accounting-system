<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;
use App\Models\Payment as PaymentModel;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\AccountingAccount;
use Illuminate\Support\Facades\DB;


class Payment extends Component
{   
    use WithPagination;

    public $amount, $sale_id, $payment_date, $method, $customer_name, $customer_phone;
    public $modal = false;
    public $search = '';
    public $paymentId;
    public $balance = 0;

    public function render()
    {
        $sales = Sale::where('user_id', auth()->id())
            ->where('payment_status', 'pendiente')
            ->where('status','pendiente')
            ->get();
        $payments = PaymentModel::with('sale')
            ->whereHas('sale', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($this->search, function ($query) {
                $query->where('amount', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('payment_date', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('method', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(6);

        return view('livewire.payment.payment', compact('sales', 'payments'));
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function resetInputFields()
    {
        $this->amount = '';
        $this->sale_id = '';
        $this->payment_date = '';
        $this->method = '';
        $this->balance = 0;
        $this->paymentId = null;
    }

    public function save()
    {
        try {
            $validatedData = Validator::make($this->modelData(), [
                'sale_id' => 'required|exists:sales,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_date' => 'required|date',
                'method' => 'required|string|max:255',
            ])->validate();

            $pay = PaymentModel::create($validatedData);

            $sale = Sale::find($this->sale_id);
            if (!$sale) {
                $this->emit('error', 'Venta no encontrada');
                return;
            }

            if ($pay->amount == $sale->total_amount) {
                $sale->update([
                    'payment_status' => 'pagado'
                ]);
            }else{
                if($pay->amount < $sale->total_amount){
                    $sale->update([
                        'payment_status' => 'pendiente'
                    ]);
                }
            }
            Log::info('Pago registrado: ' . $pay->id . ' para la venta: ' . $sale->id);
            $this->registrarAsientoContable($pay);
            Log::info('Asiento contable registrado para el pago: ' . $pay->id);

            $this->emit('paymentCreated');
            $this->closeModal();

        } catch (\Exception $e) {
            Log::error('Error al registrar el pago: ' . $e->getMessage());
            $this->emit('error', $e->getMessage());
        }
    }

    public function updatedSaleId($saleId)
    {
        $sale = Sale::find($saleId);
        $this->balance = $sale ? $sale->total_amount - $sale->payments()->sum('amount') : 0;
        $this->customer_name = $sale ? $sale->name_customer : '';
        $this->customer_phone = $sale ? $sale->phone_customer : '';
    }

    public function modelData()
    {
        return [
            'sale_id' => $this->sale_id,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'method' => $this->method,
        ];
    }

    public function registrarAsientoContable($pay){
        DB::beginTransaction();

        try {
            // Crea el asiento contable principal
            $journal = JournalEntry::create([
                'date' => now(),
                'description' => 'Pago recibido del cliente',
                'reference' => $pay->id,
                'user_id' => auth()->id(),
            ]);

            // Busca las cuentas contables
            $cuentaCaja = AccountingAccount::where('code', '1.1.01')->first(); // Caja
            $cuentaCxC = AccountingAccount::where('code', '1.1.03')->first(); // Cuentas por cobrar
            if (!$cuentaCaja || !$cuentaCxC) {
               throw new \Exception('No se encontraron las cuentas contables requeridas.');
            }
            // Línea de debe: entra dinero a Caja
            JournalEntryDetail::create([
                'journal_entry_id' => $journal->id,
                'accounting_account_id' => $cuentaCaja->id,
                'debit' => $pay->amount,
                'credit' => 0,
                'description' => 'Ingreso de dinero en caja',
            ]);

            // Línea de haber: reduce cuentas por cobrar
            JournalEntryDetail::create([
                'journal_entry_id' => $journal->id,
                'accounting_account_id' => $cuentaCxC->id,
                'debit' => 0,
                'credit' => $pay->amount,
                'description' => 'Disminución de cuentas por cobrar',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // o puedes hacer session()->flash('error', '...');
        }
    }

}
