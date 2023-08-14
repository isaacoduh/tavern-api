<?php

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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            
            $table->boolean('archived')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('approved')->default(false);

            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile_number')->unique();
            $table->string('description')->nullable();
            $table->boolean('open')->default(false);

            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('tax_id')->nullable();

            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postcode');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

            $table->double('packaging-charge')->default(0);
            $table->double('tax')->default(0);
            $table->enum('tax_type',['percent','amount'])->default('percent');
            $table->double('admin_commission')->default(0);
            $table->enum('admin_commission_type', ['percent','amount'])->default('percent');

            $table->double('rating')->default(0);
            $table->bigInteger('ratings_total')->default(0);
            $table->bigInteger('ratings_count')->default(0);

            $table->double('min_order_amount')->default(0);
            $table->boolean('take_away')->default(false);

            $table->integer('min_delivery_time')->nullable();
            $table->integer('max_delivery_time')->nullable();
            $table->enum('delivery_time_type',['minutes','hours','days'])->nullable();

            $table->boolean('open_for_delivery')->default(false);
            $table->boolean('self_delivery')->default(false);
            $table->bigInteger('delivery_range')->default(0)->nullable();
            $table->double('minimum_delivery_charge')->default(0)->nullable();
            $table->double('delivery_charge_mulitplier')->default(0)->nullable();

            // unique indexer lat, long

            

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
        Schema::dropIfExists('shops');
    }
};
