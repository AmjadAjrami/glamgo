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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained();
            $table->string('code');
            $table->integer('number_of_usage');
            $table->integer('number_of_usage_for_user');
            $table->date('date_from');
            $table->date('date_to');
            $table->double('discount');
            $table->boolean('discount_type');
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
        Schema::dropIfExists('promo_codes');
    }
};
