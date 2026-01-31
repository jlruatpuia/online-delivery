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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('deliveryboy_id')
                ->constrained('users');

            $table->decimal('amount', 10, 2);

            $table->enum('payment_type', [
                'prepaid',
                'cod'
            ]);

            $table->enum('payment_method', [
                'cash',
                'upi'
            ])->nullable(); // prepaid will be null

            $table->string('upi_ref_no')->nullable();

            $table->enum('status', [
                'pending',
                'verified',
                'rejected'
            ])->default('verified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
