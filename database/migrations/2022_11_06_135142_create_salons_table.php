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
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('cover_image')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->string('address_text');
            $table->boolean('status')->default(1);
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('password');
            $table->integer('type');
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
        Schema::dropIfExists('salons');
    }
};
