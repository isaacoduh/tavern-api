<?php

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
        Schema::create('outlet_payouts', function (Blueprint $table) {
            $table->id();
            $table->double('pay_to_outlet')->default(0);
            $table->double('take_from_outlet')->default(0);
            $table->dateTime('till_date');

            $table->foreignIdFor(Outlet::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('outlet_payouts');
    }
};
