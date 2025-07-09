<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\Category;

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

        // Insert categories
        DB::table('categories')->insert([
            [
                'id' => 1, 
                'name' => 'Makanan', 
                'icon' => '🍲', 
                'description' => 'Berbagai macam bakso dan sup',
                'is_active' => true,
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'id' => 2, 
                'name' => 'Minuman', 
                'icon' => '🥤', 
                'description' => 'Minuman segar dan menyegarkan',
                'is_active' => true,
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Insert menus
        DB::table('menus')->insert([
            // Bakso
            [
                'id' => 1,
                'name' => 'Bakso Urat',
                'description' => 'Bakso urat sapi dengan kuah kaldu yang gurih',
                'price' => 15000,
                'image' => 'menus/ycvOEHCDagy1pUcGz1hzIaYb2zgDm6MF7etsRFJL.jpg',
                'category_id' => 1,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Bakso Biasa',
                'description' => 'Bakso daging sapi dengan kuah hangat',
                'price' => 12000,
                'image' => 'menus/QuRD1HPa96cZLEAO114FPi7RVmHQaDBMU5mrywAo.jpg',
                'category_id' => 1,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Bakso Jumbo',
                'description' => 'Bakso ukuran jumbo dengan isian telur',
                'price' => 18000,
                'image' => 'menus/FVpZxNg5CSNKpjYSMwIIlVqSaOcL491ggmuEv48V.jpg',
                'category_id' => 1,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Bakso Keju',
                'description' => 'Bakso dengan isian keju mozzarella',
                'price' => 20000,
                'image' => null,
                'category_id' => 1,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Minuman
            [
                'id' => 5,
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin yang menyegarkan',
                'price' => 5000,
                'image' => 'menus/zb65DbBzxKLT8LwLEbcLgO97c4QLhj5zm5LAAhC0.jpg',
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Es Jeruk',
                'description' => 'Jeruk peras segar dengan es batu',
                'price' => 8000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Jus Alpukat',
                'description' => 'Jus alpukat segar dengan susu kental manis',
                'price' => 12000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Es Kelapa Muda',
                'description' => 'Air kelapa muda segar langsung dari buahnya',
                'price' => 10000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam pekat untuk memulai hari',
                'price' => 6000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'Es Campur',
                'description' => 'Es campur dengan berbagai topping segar',
                'price' => 15000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'name' => 'Jus Mangga',
                'description' => 'Jus mangga manis dan segar',
                'price' => 11000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'name' => 'Es Cincau',
                'description' => 'Es cincau hitam dengan sirup gula merah',
                'price' => 7000,
                'image' => null,
                'category_id' => 2,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
