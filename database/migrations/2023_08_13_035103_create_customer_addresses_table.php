<?php

use App\Models\Customer;
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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->boolean('selected')->default(false);
            $table->string('type');
            $table->string('address');
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('postcode');
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();

            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();

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
        Schema::dropIfExists('customer_addresses');
    }
};
