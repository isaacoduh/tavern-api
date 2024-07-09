<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletRevenue extends Model
{
    use HasFactory;

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    // outlet payout
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function outletPayout()
    {
        return $this->belongsTo(OutletPayout::class);
    }
}
