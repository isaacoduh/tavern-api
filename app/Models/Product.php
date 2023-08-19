<?php

namespace App\Models;

use App\Helpers\Utils\StringUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['active' => 'boolean'];

    public static function withAll()
    {
        return static::with(['category','shop']);
    }

    public function loadAll()
    {
        return $this->load(['shop','category']);
    }

    public static function extractFromData(array $validated_data)
    {
        return ['shop_id', $validated_data['shop_id'], 'description' => $validated_data['description'] ?? null, 'name' => $validated_data['name'], 'available_from' => $validated_data['available_from'] ?? null, 'available_to' => $validated_data['available_to'] ?? null];
    }

    public function scopeActive($query)
    {
        return $query->where('active',true);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model->slug = StringUtil::generateSlugFromText($model->name);
        });
    }

    // save images
    // update addons

    // public function addons
}
