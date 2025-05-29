<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

use App\Models\AccountingAccount;
use App\Http\Requests\AccountRequest;
use Stripe\FinancialConnections\Account;

class AccoutingAccount extends Component
{   
    use WithPagination;

    public $accountId, $code, $name, $type, $is_parent, $parent_account_id;
    public $modal = false;
    public $search = '';
    protected $paginationTheme = "bootstrap";
    protected $listeners = ['delete' => 'delete'];

    public function render(){  
        // $parentAccounts = AccountingAccount::where('user_id', auth()->id())
        // ->where('is_parent', true)
        // ->get(); 
        $parentAccounts = AccountingAccount::where('is_parent', true)
        ->get(); 
        
        $accounts = AccountingAccount::when($this->search, function ($query) {
                return $query->where('code', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('name', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('id', 'ASC')
            ->paginate(6);
        // $accounts = AccountingAccount::where('user_id', auth()->user()->id)
        //     ->when($this->search, function ($query) {
        //         return $query->where('code', 'LIKE', '%' . $this->search . '%')
        //             ->orWhere('name', 'LIKE', '%' . $this->search . '%');
        //     })
        //     ->orderBy('id', 'ASC')
        //     ->paginate(6);
        return view('livewire.accounts.accouting-account',compact('accounts', 'parentAccounts'));
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal(){
        $this->resetInputFields();
        $this->modal = false;
    }

    public function resetInputFields(){
        $this->accountId = null;
        $this->code = null;
        $this->name = null;
        $this->type = null;
        $this->is_parent = null;
        $this->parent_account_id = null;
    }

    public function save(){
        $validatedData = Validator::make($this->modelData(), (new AccountRequest())->rules())->validate();
   
        try {
            if ($this->accountId) {
                $account = AccountingAccount::find($this->accountId);
                $account->update($validatedData);
                $this->emit('AccountUpdated');  
            } else {
                AccountingAccount::create($validatedData);
                $this->emit('AccountCreated');  
            }
        } catch (\Exception $e) {
            Log::error('Error saving account: ' . $e->getMessage());
            $this->emit('error', $e->getMessage());
        }
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id){
        $account = AccountingAccount::find($id);
        $this->accountId = $account->id;
        $this->code = $account->code;
        $this->name = $account->name;
        $this->type = $account->type;
        $this->is_parent = $account->is_parent;
        $this->parent_account_id = $account->parent_account_id;
        $this->modal = true;
    }

    public function delete($id){
        $account = AccountingAccount::find($id);
        if ($account) {
            $account->delete();
            $this->emit('AccountDeleted');
        } else {
            $this->emit('error', 'Account not found');
        }
    }

    public function modelData(){
        return [
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'is_parent' => $this->is_parent,
            'parent_account_id' => $this->parent_account_id,
            'user_id' => auth()->user()->id,
        ];
    }
}
