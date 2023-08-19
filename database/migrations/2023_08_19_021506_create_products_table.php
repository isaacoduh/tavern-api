<?php

use App\Models\Shop;
use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');

            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();

            $table->double('rating')->default(0);
            $table->bigInteger('ratings_total')->default(0);
            $table->bigInteger('ratings_count')->default(0);
            $table->bigInteger('selling_count')->default(0);

            $table->foreignIdFor(Shop::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            
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
        Schema::dropIfExists('products');
    }
};
