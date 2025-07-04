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
        Schema::table('shifts', function (Blueprint $table) {
            // Mengubah kolom end_time agar bisa bernilai NULL
            $table->time('end_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // Mengembalikan kolom end_time menjadi tidak bisa NULL
            // Pastikan Anda tahu nilai default yang sesuai jika diperlukan
            $table->time('end_time')->nullable(false)->change();
        });
    }
};