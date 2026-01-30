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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->date('sales_date');
            $table->decimal('amount', 10, 2);

            $table->foreignId('deliveryboy_id')
                ->nullable()
                ->constrained('users');

            $table->enum('status', [
                'pending',
                'assigned',
                'delivered',
                'cancelled',
                'reschedule_requested'
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
