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
        Schema::table('event', function (Blueprint $table) {
            $table->string('event_name')->nullable()->after('id');
            $table->decimal('payment', 10, 2)->nullable()->after('event_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            Schema::dropIfExists('event_name');
            Schema::dropIfExists('payment');
        });
    }
};
