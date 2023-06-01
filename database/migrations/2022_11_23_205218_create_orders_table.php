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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->foreignIdFor(\App\Models\Cart::class)->constrained();
            $table->double('total_price');
            $table->foreignIdFor(\App\Models\PromoCode::class)->nullable()->constrained();
            $table->boolean('code_type')->nullable();
            $table->double('code_price')->nullable();
            $table->double('total_price_after_code')->nullable();
            $table->foreignIdFor(\App\Models\PaymentMethod::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Address::class)->constrained();
            $table->integer('status')->default(1);
            $table->text('cancel_reason')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
