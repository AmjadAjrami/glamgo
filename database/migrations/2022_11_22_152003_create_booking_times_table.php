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
        Schema::create('booking_times', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained()->onDelete('cascade');
            $table->string('day');
            $table->string('from');
            $table->string('to');
            $table->string('period');
            $table->boolean('is_reserved')->default(0);
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
        Schema::dropIfExists('booking_times');
    }
};
