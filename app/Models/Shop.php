<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['sellers'];

    public static function withAll()
    {
        return static::with(['sellers']);
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class);
    }

    public function owner()
    {
        return $this->hasOne(Seller::class)->where('is_owner',true);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function attachOwner($seller_id = null)
    {
        if(isset($seller_id)){
            $seller = Seller::where('is_owner',true)->find($seller_id);
            if($seller != null){
                $this->sellers()->where('is_owner',true)->where('id', '!=',$seller_id)->update(['shop_id' => null]);
                $seller->shop_id = $this->id;
                $this->sellers()->save($seller);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
