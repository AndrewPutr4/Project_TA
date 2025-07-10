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
            // ✅ Jadikan UUID sebagai primary key, ini akan otomatis unik
            $table->uuid('id')->primary();

            // Kolom untuk nomor pesanan yang bisa dibaca manusia (jika perlu)
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->text('customer_address')->nullable();
            $table->integer('table_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('service_fee');
            $table->unsignedBigInteger('total');
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->timestamp('order_date');
            $table->timestamps();
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