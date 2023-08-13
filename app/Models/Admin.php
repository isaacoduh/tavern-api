<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at' => 'datetime','active' => 'bool'];
}
