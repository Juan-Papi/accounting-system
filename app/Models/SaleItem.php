<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{   
    protected $table = 'sale_items';
    use HasFactory;
    protected $fillable = [
        'sale_id', 'product_id', 'quantity', 'price', 'total'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
