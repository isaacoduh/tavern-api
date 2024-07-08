<?php

use App\Models\Customer;
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
        Schema::create('outlet_reviews', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('rating');
            $table->string('review')->nullable();

            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Outlet::class)->constrained()->cascadeOnDelete();

            $table->unique(['outlet_id', 'customer_id']);
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
        Schema::dropIfExists('outlet_reviews');
    }
};
