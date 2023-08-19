<?php

namespace App\Models;

use App\Helpers\Utils\StringUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['complete' => 'boolean', 'ready_at' => 'datetime'];

    public static function withAll()
    {
        return static::with(['address','customer','shop']);
    }

    public  function loadAll()
    {
        return $this->load(['address','customer','shop']);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class,'customer_address_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->otp = rand(100000, 999999);
            $model->invoice_otp = StringUtil::generateRandomString(10);
        });
    }

    // can pay, can cancel, can reject, can accept, has enough stock, can ready, can deliver, can pickup, deductstock for order, paybycustomer wallet, cancel by customer, cancel by shop, rejectorder, resubmit, accept, set ready at, ready, set assign delivery boy, rejectfor delivery, accept for delivery, pickup by delivery, deliver by deliveryBOy, deliverByShop, pickupByCustomer, setAspaid, sendOrderNotification[delivery boy, seller, admin, customer], sendOrder SMS to Customer, send via email, make complate
}
