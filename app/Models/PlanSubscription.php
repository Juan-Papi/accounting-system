<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    use HasFactory;
    protected $table = "plan_subscriptions";

    protected $fillable = [
        'start_time',
        'end_time',
        'status',
        'plan_id',
        'user_id'
    ];

    public function plan(){
        return $this->belongsTo(Plan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
