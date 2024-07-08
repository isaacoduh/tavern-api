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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_name')->nullable();
            $table->string('location');
            $table->string('phone_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('imageUrl')->nullable();
            $table->boolean('active')->default(false);

            $table->double('rating')->default(0);
            $table->bigInteger('ratings_total')->default(0);
            $table->bigInteger('ratings_count')->default(0);

            $table->boolean('delivery_available')->default(false);
            $table->bigInteger('delivery_estimate_min')->nullable();
            
            $table->bigInteger('delivery_estimate_max')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->foreignId('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('outlets');
    }
};
