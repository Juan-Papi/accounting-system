<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JournalEntry;

class DayBook extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $entries = JournalEntry::with(['user', 'details.account'])
        ->when($this->search, function ($query) {
            $query->where('description', 'LIKE', '%' . $this->search . '%')
                    ->orWhereHas('details.account', function ($q) {
                        $q->where('name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('code', 'LIKE', '%' . $this->search . '%');
                    });
        })
        ->orderBy('date', 'DESC')
        ->paginate(10);

        return view('livewire.day-book.day-book',compact('entries'));
    }
}
