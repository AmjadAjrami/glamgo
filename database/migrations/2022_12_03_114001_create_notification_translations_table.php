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
        if (!Schema::hasTable('notification_translations')) {
            Schema::create('notification_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(\App\Models\Notification::class)->constrained()->onDelete('cascade');
                $table->text('title');
                $table->text('message');
                $table->string('locale');
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
        Schema::dropIfExists('notification_translations');
    }
};
