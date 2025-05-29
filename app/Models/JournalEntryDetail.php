<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryDetail extends Model
{
    use HasFactory;
    protected $table = "journal_entriy_details";

    protected $fillable = [
        'journal_entry_id', // Relación con el asiento contable principal
        'accounting_account_id', // Relación con la cuenta contable
        'debit', // Monto de debe
        'credit', // Monto de haber
        'description', // Descripción de la transacción
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(AccountingAccount::class, 'accounting_account_id');
    }
}
