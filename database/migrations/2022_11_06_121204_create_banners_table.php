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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Product::class)->nullable()->constrained();
            $table->integer('banner_type');
            $table->string('link')->nullable();
            $table->string('image');
            $table->string('type');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('banners');
    }
};
