<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Seller extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at' => 'datetime','active' => 'bool'];

    public static function withAll()
    {
        return self::with([]);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
