<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'user_id', 'total_amount', 'status', 'payment_status', 'sale_date','name_customer','phone_customer'
    ];

       public function saleItems(){
           return $this->hasMany(SaleItem::class);
       }
   
       public function payments(){
           return $this->hasMany(Payment::class);
       }

         public function user(){
              return $this->belongsTo(User::class);
         }

}
