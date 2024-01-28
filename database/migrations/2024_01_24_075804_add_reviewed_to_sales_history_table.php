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
        Schema::table('sales_history', function (Blueprint $table) {
            $table->boolean('reviewed')->after('dispatch_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_history', function (Blueprint $table) {
            $table->dropIfExists('reviewed');
        });
    }
};
