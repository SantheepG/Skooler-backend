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
        Schema::create('products', function (Blueprint $table) {
            $table->id('products_id');
            $table->string('name');
            $table->text('description');
            $table->integer('quantity');
            $table->string('size');
            $table->string('color');
            $table->decimal('price', 10, 2);
            $table->json('images');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('category');
            //foreign key


            $table->unsignedBigInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('subcategory_id')->on('subcategory');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
