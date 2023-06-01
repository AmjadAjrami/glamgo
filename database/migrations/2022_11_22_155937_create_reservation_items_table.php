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
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Reservation::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\ServiceType::class)->constrained();
            $table->foreignIdFor(\App\Models\Service::class)->constrained();
            $table->double('price');
            $table->double('discount_price')->nullable();
            $table->integer('quantity')->default(1);
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
        Schema::dropIfExists('reservation_items');
    }
};
