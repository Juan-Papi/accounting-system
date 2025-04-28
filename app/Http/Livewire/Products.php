<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Provider;
use Livewire\Component;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;

class Products extends Component
{
    use WithFileUploads;

    public $name, $description, $price, $stock, $provider_id;
    public $img_url;
    public $productId;
    public $modal = false;
    public $providers;

    protected $listeners = ['delete' => 'delete'];

    use WithPagination;
    public $buscar = '';
    protected $paginationTheme = "bootstrap";

    public function render()
    {   
        $this->providers = Provider::all();
        $products = Product::with('provider')
        ->when($this->buscar, function ($query) {
            return $query->where('name', 'LIKE', '%' . $this->buscar . '%')
                ->orWhere('description', 'LIKE', '%' . $this->buscar . '%')
                ->orWhere('price', 'LIKE', '%' . $this->buscar . '%');
        })
        ->orderBy('id', 'DESC')
        ->paginate(6);
        // dd($products);

        return view('livewire.product.products', ['products' => $products] );    
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal(){
        if ($this->img_url) {
            $this->img_url = null; 
        }
    
        $this->modal = false;
    }

    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock = 0;
        $this->provider_id = '';
        $this->img_url = null;
        $this->productId = null;
    }

    public function save(){
        $validatedData = Validator::make($this->modelData(), (new ProductRequest())->rules())->validate();
    
        try {
            if ($this->img_url) {
                if ($this->img_url instanceof \Illuminate\Http\UploadedFile && $this->img_url->isValid()) {
                    $customFileName = time() . '.' . $this->img_url->extension();
                    $imgPath = $this->img_url->storeAs('products', $customFileName, 'public');
                    $validatedData['img_url'] = $imgPath;
                } else {
                    Log::error('Archivo de imagen no válido');
                }
            }
    
            if ($this->productId) {
                $product = Product::find($this->productId);
                $product->update($validatedData);
                session()->flash('message', 'Producto actualizado correctamente');
                $this->emit('productUpdated');  // Emitir el evento

            } else {
                Product::create($validatedData);
                session()->flash('message', 'Producto creado correctamente');
                $this->emit('productCreated');  // Emitir el evento
            }
            $this->closeModal();
            $this->resetInputFields();
            
        } catch (\Exception $e) {
            Log::error('Error en save(): ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
        }
    }

    public function edit($id){
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->provider_id = $product->provider_id;
        $this->modal = true;
    }

    public function delete($id){
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            $this->emit('productDeleted');
        } else {
            $this->emit('productDeleteError');
        }
    }

    private function modelData(){
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'provider_id' => $this->provider_id,
        ];
    }

    public function limpiar_page(){
        $this->resetPage();
    }

}
