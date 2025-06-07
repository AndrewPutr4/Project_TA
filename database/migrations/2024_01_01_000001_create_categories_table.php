<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert default categories only if table exists (for fresh migration)
        if (Schema::hasTable('categories')) {
            DB::table('categories')->insert([
                ['name' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
