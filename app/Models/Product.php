<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        'name',
        'description',
        'price',
        'purchase_price',
        'stock',
        'img_url',
        'provider_id',
        'category_id',
    ];

    public function provider(){
        return $this->belongsTo(Provider::class);
    }
    
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'product_orders')
                    ->withPivot('quantity', 'subtotal', 'total_price')
                    ->withTimestamps();
    }

    public function saleItems(){
        return $this->hasMany(SaleItem::class);
    }

     public function reduceStock($quantity){
         if ($this->stock >= $quantity) {
             $this->stock -= $quantity;
             $this->save();
         } else {
             throw new \Exception('No hay suficiente stock para realizar la venta.');
         }
     }
 
    public function scopeFilterByName($query, $name){
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeFilterByCategoryName($query, $categoryName){
                return $query->whereHas('category', function ($q) use ($categoryName) {
            $q->where('name', 'like', '%' . $categoryName . '%');
        });
    }

    public function scopeFilterByProviderName($query, $providerName){
        return $query->whereHas('provider', function ($q) use ($providerName) {
            $q->where('name', 'like', '%' . $providerName . '%');
        });
    }

    public function scopeFilterByPriceRange($query, $minPrice, $maxPrice){
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeFilterByStock($query, $minStock){
        return $query->where('stock', '>=', $minStock);
    }

    public function scopeFilterByCreatedDate($query, $startDate, $endDate){
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeOrderByPrice($query, $order){
        return $query->orderBy('price', $order);
    }


}
