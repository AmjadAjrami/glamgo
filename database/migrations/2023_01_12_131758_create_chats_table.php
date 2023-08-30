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
        if (!Schema::hasTable('chats')) {
            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('provider_id');
                $table->foreignIdFor(\App\Models\User::class)->constrained();
                $table->string('firebase_id');
                $table->string('last_message')->nullable();
                $table->string('last_message_date')->nullable();
                $table->integer('provider_unread_messages')->default(0);
                $table->integer('user_unread_messages')->default(0);
                $table->boolean('provider_type');
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
        Schema::dropIfExists('chats');
    }
};
