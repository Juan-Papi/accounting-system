<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;

class DayBook extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
       $user = Auth::user();

    // Obtener IDs: el del usuario logueado y los empleados (si hay)
    $userIds = $user->employees()->pluck('id')->push($user->id);

    $entries = JournalEntry::with(['user', 'details.account'])
        ->whereIn('user_id', $userIds) // ✅ filtro por usuario o sus empleados
        ->when($this->search, function ($query) {
            $query->where('description', 'LIKE', '%' . $this->search . '%')
                  ->orWhereHas('details.account', function ($q) {
                      $q->where('name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('code', 'LIKE', '%' . $this->search . '%');
                  });
        })
        ->orderBy('date', 'DESC')
        ->paginate(10);

    return view('livewire.day-book.day-book', compact('entries'));
    }
}
