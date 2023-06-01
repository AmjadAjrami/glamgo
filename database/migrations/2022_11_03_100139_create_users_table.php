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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('mobile')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('gender')->nullable();
            $table->date('dob')->nullable();
            $table->foreignIdFor(\App\Models\Country::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\City::class)->nullable()->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->rememberToken();
            $table->boolean('status')->default(0);
            $table->double('balance')->default(0);
            $table->string('social_provider')->nullable();
            $table->longText('social_token')->nullable();
            $table->boolean('notification_active')->default(1);
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
        Schema::dropIfExists('users');
    }
};
