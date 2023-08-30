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
        if (!Schema::hasTable('withdraws')) {
            Schema::create('withdraws', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained();
                $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained();
                $table->double('amount');
                $table->string('image')->nullable();
                $table->boolean('status')->default(0);
                $table->boolean('type')->default(1);
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
        Schema::dropIfExists('withdraws');
    }
};
