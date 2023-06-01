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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\ServiceType::class)->constrained()->onDelete('cascade');
            $table->boolean('service_category');
            $table->string('image');
            $table->string('execution_time');
            $table->double('price');
            $table->double('discount_price')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_completed')->default(0);
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
        Schema::dropIfExists('services');
    }
};
