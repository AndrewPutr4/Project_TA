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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignUuid('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('kasir_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('customer_name');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('cash_received', 10, 2)->default(0);
            $table->decimal('change_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'card', 'qris', 'transfer', 'midtrans'])->default('cash');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('transaction_date');

            // ✅ KOLOM BARU UNTUK MIDTRANS (tanpa ->after())
            $table->text('snap_token')->nullable();
            $table->string('payment_gateway_reference')->nullable();
            
            $table->timestamps();

            $table->index(['transaction_date', 'status']);
            $table->index(['kasir_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};