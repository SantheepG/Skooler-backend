<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_history', function (Blueprint $table) {
            $table->id("reference_id");
            $table->unsignedBigInteger('user_id');
            $table->json('products');
            $table->dateTime('ordered_datetime');
            $table->string('payment_method');
            $table->string('order_status');
            $table->dateTime('dispatch_datetime');
            $table->longText('dispatch_address');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_history');
    }
};
