<?php

use App\Models\Order;
use App\Models\Outlet;
use App\Models\OutletPayout;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_revenues', function (Blueprint $table) {
            $table->id();

            $table->double('order_amount')->default(0);
            $table->double('tax')->default(0);
            $table->double('packaging_charge')->default(0);
            $table->double('delivery_charge')->default(0);

            $table->double('revenue');

            $table->double('pay_to_admin')->default(0);
            $table->double('take_from_admin')->default(0);
            $table->double('pay_to_delivery_agent')->default(0);
            $table->double('take_from_delivery_boy')->default(0);
            $table->double('collected_payment_from_customer')->default(0);

            $table->foreignIdFor(Outlet::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(OutletPayout::class)->nullable()->constrained()->cascadeOnDelete();
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlet_revenues');
    }
};
