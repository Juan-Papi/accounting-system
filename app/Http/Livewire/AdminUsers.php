<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
class AdminUsers extends Component
{ 
    
    use WithPagination;
    public $buscar;
    protected $paginationTheme = "bootstrap";
    

 public function render()
    {
        if (auth()->user()->hasRole('Admin')) {
            $users = User::where(function($query) {
                    $query->where('name', 'LIKE', '%' . $this->buscar . '%')
                        ->orWhere('email', 'LIKE', '%' . $this->buscar . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(6);
        } else {
            $users = User::where('parent_id', auth()->id())
                ->where(function($query) {
                    $query->where('name', 'LIKE', '%' . $this->buscar . '%')
                        ->orWhere('email', 'LIKE', '%' . $this->buscar . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(6);
        }

        return view('livewire.admin-users', compact('users'));
    }

    public function limpiar_page(){
        $this->resetPage();
    }
}
