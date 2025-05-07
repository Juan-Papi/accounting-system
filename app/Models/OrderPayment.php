<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{   
    protected $table = "order_payments";
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_date',
        'payment_method',
        'note'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
