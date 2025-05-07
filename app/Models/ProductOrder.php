<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $table = "product_orders";
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'subtotal',
        'total_price',
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function scopeFilterByOrderId($query, $orderId){
        return $query->where('order_id', $orderId);
    }
}
