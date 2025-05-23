<?php

namespace App\Models;

use function Illuminate\Events\queueable;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; //extra
use Laravel\Cashier\Billable; //ext2
use App\Models\AccountingAccount;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles; //extra
    use Billable; //ext2

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //relacion de uno a muchos
   
    //relacion de uno a muchos
    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class);
    }

    protected static function booted(): void
    {
        static::updated(queueable(function (User $customer) {
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));
    }

    public function planSubscriptions(){
        return $this->hasMany(PlanSubscription::class)->orderBy('created_at', 'desc');
    }

    public function accountingAccounts(){
        return $this->hasMany(AccountingAccount::class);
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

     // Un usuario pertenece a un gerente (parent)
    public function manager()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Un usuario puede tener varios empleados bajo su supervisión
    public function employees()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

}
