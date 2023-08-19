<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function withAll()
    {
        return static::with(['product','shop']);
    }

    public function loadAll()
    {
        return $this->load(['product','shop']);
    }

    public function getCartTotal()
    {
        // add on total
        return $this->getProductTotal();
    }

    public function getProductTotal()
    {
        return $this->quantity * $this->product->price;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
