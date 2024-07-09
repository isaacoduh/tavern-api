<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletPayout extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['till_date'];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
