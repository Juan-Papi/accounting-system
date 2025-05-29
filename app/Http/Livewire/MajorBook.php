<?php

namespace App\Http\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountingAccount;

class MajorBook extends Component
{
    use WithPagination;

    public $search = '';

   public function render()
{
    $user = Auth::user();
    $userIds = $user->employees()->pluck('id')->push($user->id);
    
    // if ($userIds->isEmpty()) {
    // $userIds = collect([$user->id]);
    // } else {
    //     $userIds->push($user->id);
    // }

    // $accounts = AccountingAccount::whereIn('user_id', $userIds)
    //     ->when($this->search, function ($query) {
    //         $query->where(function ($q) {
    //             $q->where('name', 'LIKE', '%' . $this->search . '%')
    //               ->orWhere('code', 'LIKE', '%' . $this->search . '%');
    //         });
    //     })
    //     ->orderBy('code')
    //     ->paginate(5);

    $accounts = AccountingAccount::query()
    ->when($this->search, function ($query) {
        $query->where(function ($q) {
            $q->where('name', 'LIKE', '%' . $this->search . '%')
              ->orWhere('code', 'LIKE', '%' . $this->search . '%');
        });
    })
    ->orderBy('code')
    ->paginate(5);

    foreach ($accounts as $account) {
        $details = $account->journalEntryDetails()
            ->whereHas('journalEntry', function ($q) use ($userIds) {
                $q->whereIn('user_id', $userIds);
            })
            ->with('journalEntry')
            ->get()
            ->sortBy(function ($detail) {
                return optional($detail->journalEntry)->date ?? now();
            });

        $runningBalance = 0;

        foreach ($details as $detail) {
            // Asegurar que los valores no sean nulos
            $debit = $detail->debit ?? 0;
            $credit = $detail->credit ?? 0;

            // Calcular saldo acumulado según el tipo de cuenta
            if (in_array($account->type, ['pasivo', 'patrimonio', 'ingreso'])) {
                $runningBalance += $credit - $debit;
            } else {
                $runningBalance += $debit - $credit;
            }

            // Guardar el saldo acumulado en el detalle (para mostrar en la vista)
            $detail->setAttribute('balance', $runningBalance);
        }

        // Adjuntar detalles con saldo calculado a la cuenta
        $account->setRelation('ledger', $details);
    }

    return view('livewire.major-book.major-book', compact('accounts'));
}

}
