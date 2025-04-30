<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CategoryRequest;
use Livewire\WithPagination;
use App\Models\Category;

class Categories extends Component
{               
    use WithPagination;

    public $categoryId;
    public $name, $description;
    public $modal = false;
    public $search = '';

    protected $listeners = ['delete' => 'delete'];

    public function render()
    {   $categories = Category::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(6);

        return view('livewire.categories.categories', ['categories' => $categories] );
    }

    public function openModal(){
        $this->resetInputFields();
        $this->modal = true;
    }

    public function closeModal(){
        $this->modal = false;
    }

    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
    }

    public function store(){
        $validatedData = Validator::make($this->modelData(),(new CategoryRequest())->rules())->validate();
        try {
            if ($this->categoryId) {
                $category = Category::findOrFail($this->categoryId);
                $category->update($validatedData);
                $this->emit('categoryUpdated');  
            } else {
                Category::create($validatedData);
                $this->emit('categoryCreated');  
            }

            $this->closeModal();
        
        } catch (\Exception $e) {
            Log::error('Error en save(): ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            $this->emit('error', $e->getMessage());
        }
    }

    public function edit($id){
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->modal = true;
    }

    public function delete($id){
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            $this->emit('categoryDeleted');  
        } catch (\Exception $e) {
            Log::error('Error en delete(): ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            $this->emit('error', $e->getMessage());
        }
    }

    public function modelData(){
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
