<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Seeder ini tidak diperlukan lagi karena categories sudah di-insert di MenuSeeder
        // Tapi tetap dibiarkan untuk kompatibilitas
        
        $categories = [
            [
                'name' => 'Makanan',
                'icon' => '🍲',
                'description' => 'Berbagai macam bakso dan sup'
            ],
            [
                'name' => 'Minuman',
                'icon' => '🥤',
                'description' => 'Minuman segar dan menyegarkan'
            ]
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']], 
                $category
            );
        }
    }
}
