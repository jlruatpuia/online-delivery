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
        Schema::table('settlements', function (Blueprint $table) {
            $table->decimal('total_cash', 8, 2)->after('to_date')->default(0);
            $table->decimal('total_upi', 8, 2)->after('total_cash')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settlements', function (Blueprint $table) {
            //
        });
    }
};
