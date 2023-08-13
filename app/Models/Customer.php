<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at' => 'datetime','active' => 'bool'];

    public function wallet()
    {
        return $this->hasOne(CustomerWallet::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::created(function($model){
            $wallet = new CustomerWallet();
            $wallet->customer_id = $model->id;
            $wallet->save();
        });
    }
}
