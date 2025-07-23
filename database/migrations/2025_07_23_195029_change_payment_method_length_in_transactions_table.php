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
        Schema::table('transactions', function (Blueprint $table) {
            // UBAH KOLOM payment_method MENJADI LEBIH PANJANG (misal: 50 karakter)
            $table->string('payment_method', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kembalikan ke keadaan semula jika diperlukan (opsional)
            $table->string('payment_method', 20)->change(); // Ganti 20 dengan panjang sebelumnya
        });
    }
};