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
        Schema::create('makeup_artist_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->constrained()->onDelete('cascade');
            $table->string('item');
            $table->string('thumbnail')->nullable();
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
        Schema::dropIfExists('makeup_artist_galleries');
    }
};
