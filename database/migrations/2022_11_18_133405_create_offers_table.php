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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Category::class)->constrained();
            $table->double('price');
            $table->double('discount_price');
            $table->string('image');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('offers');
    }
};
