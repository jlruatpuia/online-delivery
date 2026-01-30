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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deliveryboy_id')
                ->constrained('users');

            $table->date('from_date');
            $table->date('to_date');

            $table->decimal('total_amount', 10, 2);

            $table->enum('status', [
                'submitted',
                'approved',
                'rejected'
            ])->default('submitted');

            $table->unique(
                ['deliveryboy_id', 'from_date', 'to_date'],
                'unique_settlement_range'
            );

            $table->string('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
