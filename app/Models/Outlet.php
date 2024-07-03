<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function scopeActive($query){
        return $query->where('active', true);
    }

    // attach owner?

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
