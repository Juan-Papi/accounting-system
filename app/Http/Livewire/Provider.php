<?php

namespace App\Http\Livewire;

use App\Http\Requests\ProviderRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class Provider extends Component
{    
    use WithPagination;

    public $name, $address, $phone, $email;
    public $providerId;
    public $modal = false;
    protected $listeners = ['delete' => 'delete'];
    // public $search = '';
    public $search;
    protected $paginationTheme = "bootstrap";

    public function render(){

        $providers = \App\Models\Provider::where('name', 'LIKE', '%' . $this->search . '%')
        ->orWhere('address', 'LIKE', '%' . $this->search . '%')
        ->orWhere('phone', 'LIKE', '%' . $this->search . '%')
        ->orderBy('id', 'DESC')
        ->paginate(6);
        return view('livewire.provider.provider', ['providers' => $providers]);
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal(){
        $this->modal = false;
    }

    public function resetInputFields(){
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
    }
    public function save(){
        try {
            $validatedData = Validator::make($this->modelData(), (new ProviderRequest())->rules())->validate();

            if($this->providerId){
                $provider = \App\Models\Provider::find($this->providerId);
                $provider->update($validatedData);
                $this->emit('providerUpdated');
            } else {
                \App\Models\Provider::create($validatedData);
                $this->emit('providerCreated');
            }
            $this->resetInputFields();
            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Error en save(): ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
        }
    }

    public function edit($id){
        $this->modal = true;
        $provider = \App\Models\Provider::findOrFail($id);
        $this->name = $provider->name;
        $this->address = $provider->address;
        $this->phone = $provider->phone;
        $this->email = $provider->email;
        $this->providerId = $id;
    }

    public function delete($id){
        $provider = \App\Models\Provider::find($id);
        if ($provider) {
            $provider->delete();
            $this->emit('providerDeleted');
        } else {
            $this->emit('providerDeleteError');
        }
    }
    private function modelData(){
        return [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
    public function limpiar_page(){
        $this->resetPage();
    }
}
