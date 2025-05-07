<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;
    protected $table = "journal_entries";

    protected $fillable = [
        'date',
        'description',
        'reference', // número o código de referencia opcional
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function details()
    // {
    //     return $this->hasMany(JournalEntryDetail::class);
    // }
}
