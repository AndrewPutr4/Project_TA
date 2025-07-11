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
        Schema::create('orders', function (Blueprint $table) {
            // ✅ Primary key
            $table->uuid('id')->primary();

            // ✅ Order details
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->integer('table_number')->nullable();
            $table->text('notes')->nullable();

            // ✅ Financials (Corrected to use decimal for currency)
            $table->decimal('subtotal', 10, 2);
            $table->decimal('service_fee', 10, 2);
            $table->decimal('total', 10, 2);

            // ✅ Statuses
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('unpaid'); // <-- Column added

            // ✅ Timestamps
            $table->timestamp('order_date')->useCurrent(); // Sets the order date automatically
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};