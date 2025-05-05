<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;
    protected $table = "qr";


    protected $fillable = ['plan_id', 'price', 'qr_base64', 'status','motion_id'];
    
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
