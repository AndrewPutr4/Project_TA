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
            $table->id();
            $table->string('order_number')->unique()->nullable();
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->text('customer_address');
            $table->json('items');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedInteger('delivery_fee')->default(0);
            $table->unsignedInteger('service_fee')->default(0);
            $table->unsignedBigInteger('total');
            $table->string('status', 191)->default('pending');
            $table->string('payment_status', 191)->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('order_date', 3)->useCurrent();
            $table->unsignedBigInteger('table_number')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('status');
            $table->index('order_date');
            $table->index('customer_phone');
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