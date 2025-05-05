<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = "plans";

    protected $fillable = [
        'name',
        'price',
        'duration_days'
        ];

    public function detailPlans(){
        return $this->hasMany(DetailPlan::class);
    }

    public function planSubscriptions(){
        return $this->hasMany(PlanSubscription::class);
    }

    public function qr(){
        return $this->hasMany(Qr::class);
    }
}
