<?php

use App\Models\CustomerWallet;
use App\Models\Order;
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
        Schema::create('customer_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->boolean('added')->default(false);
            $table->double('amount');
            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();

            $table->foreignIdFor(CustomerWallet::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('customer_wallet_transactions');
    }
};
