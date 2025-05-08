<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'sale_id', 'amount', 'payment_date', 'method'
    ];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
