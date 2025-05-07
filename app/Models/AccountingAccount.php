<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingAccount extends Model
{
    use HasFactory;
    protected $table = "accounting_accounts";

    protected $fillable = [
        'code',
        'name',
        'type',
        'is_parent',
        'parent_account_id',
        'user_id',
    ];

    public function parent()
    {
        return $this->belongsTo(AccountingAccount::class, 'parent_account_id');
    }

    public function children()
    {
        return $this->hasMany(AccountingAccount::class, 'parent_account_id');
    }

    public function journalEntryDetails(){
        return $this->hasMany(JournalEntryDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
