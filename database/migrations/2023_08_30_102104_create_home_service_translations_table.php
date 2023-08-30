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
        Schema::create('home_service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\HomeService::class)->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('bio');
            $table->string('locale');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_service_translations');
    }
};
