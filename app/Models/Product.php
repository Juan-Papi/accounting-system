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
        'stock',
        'img_url',
        'provider_id',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
