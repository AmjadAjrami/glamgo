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
        if (!Schema::hasTable('salon_settings')) {
            Schema::create('salon_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained()->onDelete('cascade');
                $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained()->onDelete('cascade');
                $table->string('key');
                $table->string('value');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salon_settings');
    }
};
