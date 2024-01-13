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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id("complaint_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("product_id");
            $table->string("description");
            $table->string("status");
            $table->json('images')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('products_id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
