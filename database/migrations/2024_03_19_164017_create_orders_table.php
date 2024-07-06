<?php

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Shop;
use App\Models\Outlet;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('otp');
            $table->string('invoice_otp');
            $table->boolean('complete')->default(false);
            $table->timestamp('ready_at')->nullable();
            $table->string('notes')->nullable();

            $table->enum('order_type', ['delivery', 'self_pick_up','pos'])->default('delivery');

            $table->double('order_amount');
            $table->double('packing_charge')->default(0);
            $table->double('tax')->default(0);
            $table->double('order_commission')->default(0);

            $table->double('delivery_charge')->default(0);
            $table->double('minimum_delivery_charge')->default(0);
            $table->double('delivery_charge_multiplier')->default(0);
            $table->mediumInteger('delivery_distance')->default(0);

            $table->double('coupon_discount')->default(0);

            $table->double('payment_charge')->default(0);

            $table->double('paid_amount')->default(0);
            $table->double('change_amount')->default(0);

            $table->double('total');

            $table->double('admin_revenue_amount')->default(0);
            $table->double('delivery_boy_revenue_amount')->default(0);
            $table->double('shop_revenue_amount')->default(0);


            $table->enum('payment_type', ['cash_on_delivery', 'wallet', 'card'])->default('cash_on_delivery');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->float('total_payment');
            $table->float('payment')->default(0);

             // order status
            $table->enum('status', ['order_placed', 'payment_done', 'cancelled_by_customer', 'cancelled_by_outlet', 'accepted','rejected','resubmit', 'processing', 'assign_delivery_agent', 'accept_for_delivery', 'reject_for_delivery','order_ready','on_the_way','delivered','reviewed'])->default('order_placed');
            $table->string('status_description')->nullable();

            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            // $table->foreignIdFor(Shop::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(CustomerAddress::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Outlet::class)->nullable()->constrained()->nullOnDelete();

            // coupon, deliveryboy, assign_delivery

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
        Schema::dropIfExists('orders');
    }
};
