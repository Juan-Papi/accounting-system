<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        'quantity',
        'total_price',
        'status',
        'user_id',
        'provider_id',
        'balance'
    ];


    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_orders')
                    ->withPivot('quantity', 'subtotal', 'total_price')
                    ->withTimestamps();
    }

    public function payments(){
        return $this->hasMany(OrderPayment::class);
    }


}
