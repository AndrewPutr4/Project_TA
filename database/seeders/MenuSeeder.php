<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data dengan delete agar tidak error foreign key constraint
        DB::table('menus')->delete();
        DB::table('categories')->delete();

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Bakso', 'icon' => '🍲', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Minuman', 'icon' => '🥤', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('menus')->insert([
            'id' => 1,
            'name' => 'Bakso Urat',
            'price' => 15000,
            'image' => 'menus/ycvOEHCDagy1pUcGz1hzIaYb2zgDm6MF7etsRFJL.jpg',
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

