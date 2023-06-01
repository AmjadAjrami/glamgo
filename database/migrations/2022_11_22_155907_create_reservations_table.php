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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->foreignIdFor(\App\Models\Salon::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\MakeupArtist::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Offer::class)->nullable()->constrained();
            $table->integer('day')->nullable();
            $table->date('date')->nullable();
            $table->foreignIdFor(\App\Models\BookingTime::class)->nullable()->constrained();
            $table->text('cancel_reason')->nullable();
            $table->text('reject_reason')->nullable();
            $table->foreignIdFor(\App\Models\PromoCode::class)->nullable()->constrained();
            $table->boolean('code_type')->nullable();
            $table->double('code_price')->nullable();
            $table->double('total_price');
            $table->double('total_price_after_code')->nullable();
            $table->foreignIdFor(\App\Models\PaymentMethod::class)->nullable()->constrained();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('reservations');
    }
};
