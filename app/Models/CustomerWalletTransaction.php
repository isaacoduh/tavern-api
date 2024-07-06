<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWalletTransaction extends Model
{
    use HasFactory;

    public static string $stripe_method = 'stripe';
    public static string $from_admin_method = 'from_admin';

    protected $casts = ['added' => 'boolean'];
}
