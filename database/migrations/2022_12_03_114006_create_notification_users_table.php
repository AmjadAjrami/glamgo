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
        if (!Schema::hasTable('notification_users')) {
            Schema::create('notification_users', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(\App\Models\Notification::class)->constrained()->onDelete('cascade');
                $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('notification_users');
    }
};
